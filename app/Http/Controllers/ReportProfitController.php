<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Vendor;
use App\Model\VendorInvoice;
use Yajra\DataTables\Facades\DataTables;

class ReportProfitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {

            $query = VendorInvoice::with(['shippings','shippings.shipping'])
                                ->where('profit', '>', 0)
                                 ->orderBy('date', 'desc')
                                 ->when(request('dateStart') && request('dateEnd'), function ($q) {
                                     $q->whereBetween('date', [request('dateStart'), request('dateEnd')]);
                                 })
                                 ->when(request('vendor_id'), function ($q) {
                                     $q->where('vendor_id', request('vendor_id'));
                                 });
            $data = $query->get();
            $totalOmset = $data->sum('omset');
            $totalProfit = $data->sum('profit');
            $totalHpp = $data->sum('amount');
            $total = $totalOmset + $totalProfit + $totalHpp;
            return DataTables::of($data)
                ->with('totalOmset', $totalOmset)
                ->with('totalProfit', $totalProfit)
                ->with('totalHpp', $totalHpp)
                ->make(true);
        }
        $d['vendors'] = Vendor::all();
        return view('report-profit.index', $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
