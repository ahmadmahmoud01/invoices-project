<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('reports.invoices', compact('invoices'));
    }

    public function search(Request $request)
    {
        $radio = $request->input('radio');
        $invoices = collect(); // Default to an empty collection
        $type = $request->input('type', null);
        $start_at = $request->input('start_at', null);
        $end_at = $request->input('end_at', null);

        // Search by invoice type
        if ($radio == 1 && $type) {
            // Determine if date range is provided
            if ($start_at && $end_at) {
                // Format dates to ensure correct format
                $start_at = date($start_at);
                $end_at = date($end_at);

                $invoices = Invoice::where('status', $type)
                    ->whereBetween('invoice_Date', [$start_at, $end_at])
                    ->get();
            } else {
                // Search by type only if no date range is specified
                $invoices = Invoice::where('status', $type)->get();
            }
        } elseif ($radio == 2) {
            // Search by invoice number
            $invoice_number = $request->input('invoice_number');
            if ($invoice_number) {
                $invoices = Invoice::where('number', $invoice_number)->get();
            }
        }

        // Pass necessary data to the view
        return view('reports.invoices', compact('invoices', 'type', 'start_at', 'end_at'));
    }
}
