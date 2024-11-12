<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use Log;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreInvoiceRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AddInvoiceNotification;
use Spatie\Permission\Contracts\Role;

use function Laravel\Prompts\alert;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('invoices.create', [
            'sections' => Section::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        DB::beginTransaction();

        try {
            // Create the invoice
            $invoice = Invoice::create([
                'number' => $request->number,
                'date' => $request->date,
                'due_date' => $request->due_date,
                'product' => $request->product,
                'section_id' => $request->section_id,
                'collection_amount' => $request->collection_amount,
                'commission_amount' => $request->commission_amount,
                'discount' => $request->discount,
                'vat_value' => $request->vat_value,
                'vat_rate' => $request->vat_rate,
                'total' => $request->total,
                'status' => 'غير مدفوعة',
                'value_status' => 2,
                'note' => $request->note,
            ]);

            // Create the invoice detail
            InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                // 'invoice_number' => $request->number,
                // 'product' => $request->product,
                'section_id' => $invoice->section->id,
                'status' => 'غير مدفوعة',
                'value_status' => 2,
                'note' => $request->note,
                'user_id' => Auth::user()->id,
            ]);

            // Handle attachments if they exist
            if ($request->hasFile('pic')) {
                $image = $request->file('pic');
                $file_name = $image->getClientOriginalName();
                $invoice_number = $request->number;

                // Create invoice attachment
                InvoiceAttachment::create([
                    'file_name' => $file_name,
                    'invoice_number' => $invoice_number,
                    'created_by' => Auth::user()->name,
                    'invoice_id' => $invoice->id,
                ]);

                // Move the uploaded file
                $request->pic->move(public_path('Attachments/' . $invoice_number), $file_name);
                // Store in the attachments folder inside public
                // $path = $image->store('Attachments', 'public');
            }

            // $user = User::all();
            $user = User::role('Admin')->get();
            // $user->notify(new AddInvoiceNotification($invoice));

            Notification::send($user, new AddInvoiceNotification($invoice));

            DB::commit();
            return back()->with('success', 'تم اضافة الفاتورة بنجاح');
        } catch (\Exception $e) {

            DB::rollBack();
            alert($e);
            \Log::error('Error in InvoiceController@store: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء إضافة الفاتورة.']);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = Invoice::findOrFail($id);
        $attachment = InvoiceAttachment::where('invoice_id', $id)->first();

        if ($request->page_id != 2) {

            if ($attachment && !empty($attachment->invoice_number)) {
                Storage::disk('public_uploads')->deleteDirectory($attachment->invoice_number);
            }

            $invoice->forceDelete();
            return back()->with('success', 'تم حذف الفاتورة بنجاح');
        } else {
            $invoice->delete();
            return back()->with('success', 'تم أرشفة الفاتورة بنجاح');
        }
    }


    public function editStatus($id)
    {
        $invoice = Invoice::find($id);
        return view('invoices.update_invoice_status', compact('invoice'));
    }

    public function updateStatus(Request $request, $id)
    {

        $invoice = Invoice::findOrFail($id);

        $valueStatus = $request->status === 'مدفوعة' ? 1 : 3;

        $invoice->update([
            'value_status' => $valueStatus,
            'status' => $request->status,
            'payment_date' => $request->payment_date,
        ]);

        InvoiceDetail::create([
            'invoice_id' => $request->invoice_id,
            'section_id' => $request->section,
            'status' => $request->status,
            'value_status' => $valueStatus,
            'note' => $request->note,
            'payment_date' => $request->payment_date,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('invoices.index')->with('success', 'تم تحديث حالة الدفع بنجاح');
    }

    // paid invoices
    public function paidInvoices()
    {
        return view('invoices.paid', [
            'invoices' => Invoice::where('value_status', 1)->get()
        ]);
    }

    // unpaid invoices
    public function unPaidInvoices()
    {
        return view('invoices.unpaid', [
            'invoices' => Invoice::where('value_status', 2)->get()
        ]);
    }

    // partially paid invoices
    public function partiallyPaidInvoices()
    {
        return view('invoices.partially-paid', [
            'invoices' => Invoice::where('value_status', 3)->get()
        ]);
    }

    // archived invoices
    public function archivedInvoices()
    {
        return view('invoices.archived', [
            'invoices' => Invoice::onlyTrashed()->get()
        ]);
    }

    // print invoice
    public function printInvoice(Invoice $invoice) {
        return view('invoices.print', compact('invoice'));
    }

    // export all invoices
    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
}
