<?php

namespace App\Http\Controllers;

use App\Model\Vendor;
use App\Model\VendorSpending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\VendorInvoice;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class TrackingController extends Controller
{
    public function index(){
        if (request()->ajax()) {
            $query = VendorInvoice::with(['shippings', 'shippings.shipping']);

            if (request('vendor_id')) {
                $query = $query->where('vendor_id', request('vendor_id'));
            }

            if (request('invoice_type')) {
                if (request('invoice_type') == 'finish') {
                    $query = $query->where('remaining_amount', 0);
                }

                if (request('invoice_type') == 'unfinished') {
                    $query = $query->where('remaining_amount', '>', 0);
                }
            }

            if (request('dateStart') && request('dateEnd')) {
                $query->whereBetween('date', [request('dateStart'), request('dateEnd')]);
            }
            return DataTables::of($query->orderBy('date', 'desc')->get())->make(true);
        }


        $d['vendors'] = Vendor::all();

        return view('tracking.index', $d);
    }

    public function create(){

        return view('tracking.create');
    }
}
