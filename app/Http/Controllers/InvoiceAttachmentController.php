<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentController extends Controller
{
    public function destroy(Request $request)
    {
        $invoice = InvoiceAttachment::findOrFail($request->file_id);
        $invoice->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number . '/' . $request->file_name);
        return back();
    }

    public function get_file($invoice_number, $file_name)
    {
        $path = $invoice_number . '/' . $file_name;
        $fullPath = Storage::disk('public_uploads')->path($path);
        return response()->download($fullPath);
    }



    public function open_file($invoice_number, $file_name)
    {
        $path = $invoice_number . '/' . $file_name;
        $fullPath = Storage::disk('public_uploads')->path($path);
        return response()->file($fullPath);
    }

    public function store(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'file_name' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'invoice_number' => 'required',
            'invoice_id' => 'required|exists:invoices,id',
        ], [
            'file_name.mimes' => 'صيغة المرفق يجب ان تكون pdf, jpeg, png, jpg',
            'file_name.max' => 'حجم الملف لا يجب أن يتجاوز 2 ميجابايت',
        ]);

        try {
            $file = $request->file('file_name');
            $fileName = $file->getClientOriginalName();
            $invoiceNumber = $request->invoice_number;

            // Store the file
            $file->storeAs(
                $invoiceNumber,
                $fileName,
                'public_uploads'
            );

            // Create attachment record
            InvoiceAttachment::create([
                'file_name' => $fileName,
                'invoice_number' => $invoiceNumber,
                'invoice_id' => $request->invoice_id,
                'created_by' => auth()->user()->name,
            ]);

            return back()->with('success', 'تم اضافة المرفق بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء رفع الملف');
        }
    }
}
