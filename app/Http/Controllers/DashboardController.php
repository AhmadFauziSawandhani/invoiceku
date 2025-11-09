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
    public function index(){
        $today = Carbon::today();
        $month = Carbon::now()->month;

        // 🔹 1. Keuntungan hari ini (per produk)
        $data['invoices_today'] = InvoiceItem::with('product')
            ->whereHas('invoice', function($q) use ($today) {
                $q->whereDate('invoice_date', $today);
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_amount'),
                DB::raw('SUM((selling_price - buying_price) * quantity) as total_profit')
            )
            ->groupBy('product_id')
            ->get();

        // 🔹 2. Keuntungan bulan ini
        $data['invoices_month'] = InvoiceItem::whereHas('invoice', function($q) use ($month) {
                $q->whereMonth('invoice_date', $month);
            })
            ->select(
                DB::raw('SUM((selling_price - buying_price) * quantity) as total_profit')
            )
            ->first();

        // 🔹 3. Total profit semua waktu
        $data['total_profit_all'] = InvoiceItem::select(
                DB::raw('SUM((selling_price - buying_price) * quantity) as total_profit')
            )
            ->value('total_profit');

        // 🔹 Total hari ini (dari hasil join produk)
        $data['total_profit_today'] = $data['invoices_today']->sum('total_profit');

        // 🔹 Total bulan ini (ambil dari query)
        $data['total_profit_month'] = $data['invoices_month']->total_profit ?? 0;

        return view('dashboard.index', $data);
    }

    public function print(){
        $today = now()->toDateString();
        $invoices = InvoiceItem::with('product')
            ->whereHas('invoice', function($q) use ($today) {
                $q->whereDate('invoice_date', $today);
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty')
            )
            ->groupBy('product_id')
            ->get();

        $pdf = PDF::loadView('dashboard.print', compact('invoices', 'today'))
            ->setPaper('A4', 'portrait');

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
