<?php

namespace App\Http\Controllers;

use App\Model\PaymentHistory;
use App\Model\Vendor;
use App\Model\VendorInvoice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardVendorController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = PaymentHistory::with('vendor');

            if ($request->vendor_id) {
                $query = $query->where('vendor_id', $request->vendor_id);
            }

            if ($request->dateStart && $request->dateEnd) {
                $query->whereBetween('date', [$request->dateStart, $request->dateEnd]);
            }
            return DataTables::of($query->orderBy('date', 'desc')->get())->make(true);
        }


        $d['vendors'] = Vendor::all();

        $d['totalInvoice'] = PaymentHistory::where('type', PaymentHistory::TYPE_INVOICE)->sum('amount');
        $d['totalInvoiceMonth'] = VendorInvoice::whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('amount');
        $d['qtyInvoiceMonth'] = VendorInvoice::whereMonth('date', date('m'))->whereYear('date', date('Y'))->count();
        $d['payment'] = PaymentHistory::where('type', PaymentHistory::TYPE_PAYMENT)->sum('amount');
        $d['paymentMonth'] = PaymentHistory::whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('amount');
        $d['saldoTotal'] = $d['totalInvoice'] - $d['payment'];
        $d['saldoMonth'] = $d['totalInvoiceMonth'] - $d['paymentMonth'];
        $d['due_date'] = VendorInvoice::where('due_date', '<', date('Y-m-d'))->count();
        $d['profit'] = VendorInvoice::whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('profit');

        return view('dashboard.vendor', $d);
    }

    public function show($id)
    {
        $vendor = Vendor::find($id);
        if (request()->ajax()) {
            $data = [];
            $query = PaymentHistory::where('ref_id', $id);
            $data = $query->orderBy('date', 'asc')->get();
            $totalInvoice = $data->where('type', 'invoice')->sum('amount');
            $totalPayment = $data->where('type', 'payment')->sum('amount');
            return DataTables::of($data)
                ->with('totalInvoice', $totalInvoice)
                ->with('totalPayment', $totalPayment)
                ->with('totalSaldo', $totalInvoice - $totalPayment)
                ->make(true);
            return DataTables::of($data)->with('totalInvoice', 0)->with('totalPayment', 0)->make(true);
        }

        return view('dashboard.show', compact('vendor'));
    }
}
