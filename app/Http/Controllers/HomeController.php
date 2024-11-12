<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $total_invoices = Invoice::count();
        $paid_count = Invoice::where('value_status', 1)->count();
        $unpaid_count = Invoice::where('value_status', 2)->count();
        $partial_paid_count = Invoice::where('value_status', 3)->count();

        $paid_percentage = $total_invoices > 0 ? round(($paid_count / $total_invoices) * 100, 2) : 0;
        $unpaid_percentage = $total_invoices > 0 ? round(($unpaid_count / $total_invoices) * 100, 2) : 0;
        $partial_paid_percentage = $total_invoices > 0 ? round(($partial_paid_count / $total_invoices) * 100, 2) : 0;

        $chart = Chartjs::build()
            ->name('invoiceStatusChart')
            ->type('bar')
            ->size(['width' => 200, 'height' => 100])
            ->labels(['Unpaid', 'Paid', 'Partially Paid'])
            ->datasets([
                [
                    "label" => "Unpaid Invoices",
                    'backgroundColor' => ['rgba(255, 99, 132, 0.2)'],
                    'data' => [$unpaid_percentage]
                ],
                [
                    "label" => "Paid Invoices",
                    'backgroundColor' => ['rgba(54, 162, 235, 0.2)'],
                    'data' => [$paid_percentage]
                ],
                [
                    "label" => "Partially Paid Invoices",
                    'backgroundColor' => ['rgba(255, 206, 86, 0.2)'],
                    'data' => [$partial_paid_percentage]
                ]
            ])
            ->options([
                "scales" => [
                    "y" => [
                        "beginAtZero" => true,
                        "max" => 100  // Set max to 100 for percentage scale
                    ]
                ]
            ]);

        $chart2 = Chartjs::build()
            ->name('pieChartInvoiceStatus')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Paid Invoices', 'Unpaid Invoices', 'Partially Paid Invoices'])
            ->datasets([
                [
                    'backgroundColor' => ['#36A2EB', '#FF6384', '#FFCE56'],
                    'hoverBackgroundColor' => ['#36A2EB', '#FF6384', '#FFCE56'],
                    'data' => [$paid_percentage, $unpaid_percentage, $partial_paid_percentage]
                ]
            ])
            ->options([]);

        return view('home', compact('chart', 'chart2'));
    }
}
