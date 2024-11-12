<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Storage;

class InvoiceArchiveController extends Controller
{
    public function destroy($id)
    {

        $invoice = Invoice::onlyTrashed()->findOrFail($id);
        $attachment = InvoiceAttachment::where('invoice_id', $id)->first();

        if ($attachment && !empty($attachment->invoice_number)) {
            Storage::disk('public_uploads')->deleteDirectory($attachment->invoice_number);
        }

        $invoice->forceDelete();
        return back()->with('success', 'تم حذف الفاتورة نهائيا بنجاح');
    }

    public function restore($id)
    {
        $invoice = Invoice::withTrashed()->find($id);

        if (!$invoice) {
            return back()->with('error', 'الفاتورة غير موجودة');
        }

        $invoice->restore();

        return back()->with('success', 'تم استعادة الفاتورة بنجاح');
    }
}
