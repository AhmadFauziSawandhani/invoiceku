<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Model\Asset;
use App\Model\Asset_detail;
use App\Model\FinancialRecap;
use App\Model\Manifest;
use App\Model\OfficeSpending;
use App\Model\PaymentHistory;
use App\Model\InvoiceItem;
use App\Model\Invoice;
use App\Model\Shipping;
use App\Model\Vendor;
use App\Model\VendorSpending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\VendorInvoice;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $today = Carbon::today();   
    //     $data['invoices'] = InvoiceItem::with('product')
    //         ->whereHas('invoice', function($q) use ($today) {
    //             $q->whereDate('invoice_date', $today);
    //         })
    //         ->select('product_id', 
    //             DB::raw('SUM(quantity) as total_qty'),
    //             DB::raw('SUM(subtotal) as total_amount'),
    //             DB::raw('SUM((selling_price - buying_price) * quantity) as total_profit')
    //         )
    //         ->groupBy('product_id')
    //         ->get();

    //     $data['total_profit'] = $data['invoices']->sum('total_profit');
        
    //     return view('dashboard.index', $data);
    // }
    // public function index(){
    //     $today = Carbon::today();
    //     $month = Carbon::now()->month;

    //     // 🔹 1. Keuntungan hari ini (per produk)
    //     $data['invoices_today'] = InvoiceItem::with(['product', 'invoice'])
    //         ->whereHas('invoice', function($q) use ($today) {
    //             $q->whereDate('invoice_date', $today);
    //         })
    //         ->select(
    //             'product_id',
    //             DB::raw('SUM(quantity) as total_qty'),
    //             DB::raw('SUM(subtotal) as total_amount'),
    //             DB::raw('SUM((selling_price - buying_price) * quantity) as total_profit')
    //         )
    //         ->groupBy('product_id')
    //         ->get();

    //     // 🔹 2. Keuntungan bulan ini
    //     $data['invoices_month'] = InvoiceItem::whereHas('invoice', function($q) use ($month) {
    //             $q->whereMonth('invoice_date', $month);
    //         })
    //         ->select(
    //             DB::raw('SUM((selling_price - buying_price) * quantity) as total_profit')
    //         )
    //         ->first();

    //     // 🔹 3. Total profit semua waktu
    //     $data['total_profit_all'] = InvoiceItem::select(
    //             DB::raw('SUM((selling_price - buying_price) * quantity) as total_profit')
    //         )
    //         ->value('total_profit');

    //     // 🔹 Total hari ini (dari hasil join produk)
    //     $data['total_profit_today'] = $data['invoices_today']->sum('total_profit');

    //     // 🔹 Total bulan ini (ambil dari query)
    //     $data['total_profit_month'] = $data['invoices_month']->total_profit ?? 0;

    //     return view('dashboard.index', $data);
    // }

    public function index(){
        $today = Carbon::today();
        $month = Carbon::now()->month;

        // 🔹 1. Semua invoice hari ini (lengkap dengan customer & produk)
        $data['invoices_today'] = Invoice::with(['customer', 'items.product'])
                ->where('status', 'PAID')
            ->whereDate('invoice_date', $today)
            ->get();

        // 🔹 2. Ringkasan produk hari ini (total per produk)
        $items_today = InvoiceItem::with(['product', 'invoice.customer'])
            ->whereHas('invoice', function ($q) use ($today) {
                $q->where('status', 'PAID')  // ✅ hanya ambil invoice PAID
                ->whereDate('invoice_date', $today);
            })
            ->get()
            ->groupBy('product_id')
            ->map(function ($group) {
                $first = $group->first();

                // Group per customer dalam produk ini
                $customers = $group
                    ->groupBy(fn($item) => $item->invoice->customer->name ?? 'Unknown')
                    ->map(function ($custItems) {
                        return [
                            'customer_name' => $custItems->first()->invoice->customer->name ?? 'Unknown',
                            'quantity' => $custItems->sum('quantity'),
                            'unit' => $custItems->first()->product->unit ?? '',
                        ];
                    })
                    ->values();

                    $total_profit = $group->sum(function ($item) {
                        return ($item->selling_price - $item->buying_price) * $item->quantity;
                    });
                    return [
                        'product' => $first->product,
                        'total_qty' => $group->sum('quantity'),
                        'customers' => $customers,
                        'total_profit' => $total_profit,
                    ];
            })
            ->values();

        $data['items_summary_today'] = $items_today;

        // 🔹 3. Total keuntungan bulan ini
        $data['invoices_month'] = InvoiceItem::whereHas('invoice', function($q) use ($month) {
                $q->where('status', 'PAID')
                ->whereMonth('invoice_date', $month);
            })
            ->select(DB::raw('SUM((selling_price - buying_price) * quantity) as total_profit'))
            ->first();

        // 🔹 4. Total profit semua waktu
         $data['total_profit_all'] = InvoiceItem::whereHas('invoice', function($q) {
            $q->where('status', 'PAID');
        })
            ->select(DB::raw('SUM((selling_price - buying_price) * quantity) as total_profit'))
            ->value('total_profit');

        // 🔹 5. Total profit hari ini & bulan ini
        $data['total_profit_today'] = $items_today->sum('total_profit');

        $data['total_profit_month'] = $data['invoices_month']->total_profit ?? 0;

        return view('dashboard.index', $data);
    }

    // public function print(){
    //     $today = now()->toDateString();
    //     $invoices = InvoiceItem::with('product')
    //         ->whereHas('invoice', function($q) use ($today) {
    //             $q->whereDate('invoice_date', $today);
    //         })
    //         ->select(
    //             'product_id',
    //             DB::raw('SUM(quantity) as total_qty')
    //         )
    //         ->groupBy('product_id')
    //         ->get();

    //     $pdf = PDF::loadView('dashboard.print', compact('invoices', 'today'))
    //         ->setPaper('A4', 'portrait');

    //     return $pdf->stream('laporan-harian-' . date('Ymd') . '.pdf');
    // }

    public function print(){
        $today = now()->toDateString();

        // Ambil data invoice items hari ini lengkap dengan relasi
        $items_today = InvoiceItem::with(['product', 'invoice.customer'])
            ->whereHas('invoice', fn($q) => $q->whereDate('invoice_date', $today))
            ->get()
            ->groupBy('product_id')
            ->map(function ($group) {
                $first = $group->first();

                // Group lagi per customer dalam produk ini
                $customers = $group
                    ->groupBy(fn($item) => $item->invoice->customer->name ?? 'Unknown')
                    ->map(function ($custItems) {
                        return [
                            'customer_name' => $custItems->first()->invoice->customer->name ?? 'Unknown',
                            'quantity' => $custItems->sum('quantity'),
                            'unit' => $custItems->first()->product->unit ?? '',
                        ];
                    })
                    ->values();

                return [
                    'product' => $first->product,
                    'total_qty' => $group->sum('quantity'),
                    'customers' => $customers,
                ];
            })
            ->values();

        $pdf = PDF::loadView('dashboard.print', [
            'today' => $today,
            'items_summary_today' => $items_today,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-harian-' . date('Ymd') . '.pdf');
    }

    public function dashboardGudang(){

        $totalManifestBulanIni = Manifest::whereMonth('date_manifest', date('m'))->whereYear('date_manifest', date('Y'))->count();
        $manifestHariIni = Manifest::where('date_manifest', date('Y-m-d'))->count();
        $notSend = Manifest::whereNull('warehouse_exit_date')->count();
        return view('dashboard.gudang', compact('totalManifestBulanIni', 'manifestHariIni', 'notSend'));

    }

    public function dashboardTracking(Request $request){

        if ($request->ajax()) {
            $data = Manifest::query()
                ->has('tracking')
                ->with('tracking', 'lastTracking', 'product')
                ->when($request->vendor_id, function ($query) use ($request) {
                    $query->whereHas('tracking', function ($query) use ($request) {
                        $query->where('vendor_id', $request->vendor_id);
                    });
                })
                ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                    $query->whereBetween('date_manifest', [$request->start_date, $request->end_date]);
                });

            return DataTables::eloquent($data)->make(true);
        }


        $d['vendors'] = Vendor::all();

        return view('dashboard.tracking',$d);
    }

}
