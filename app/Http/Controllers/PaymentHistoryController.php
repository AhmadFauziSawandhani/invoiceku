<?php

namespace App\Http\Controllers;

use App\Exports\PaymentHistoryExport;
use App\Imports\PaymentHistoryImport;
use App\Model\PaymentHistory;
use App\Model\Vendor;
use App\Model\VendorInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PaymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (request()->ajax()) {
            $data = [];
            if ($request->vendor_id) {
                $query = PaymentHistory::where('vendor_id', $request->vendor_id);

                if ($request->dateStart && $request->dateEnd) {
                    $query->whereBetween('date', [$request->dateStart, $request->dateEnd]);
                }
                $data = $query->orderBy('date', 'asc')->get();
                $totalInvoice = $data->where('type', 'invoice')->sum('amount');
                $totalPayment = $data->where('type', 'payment')->sum('amount');
                return DataTables::of($data)
                ->with('totalInvoice', $totalInvoice)
                ->with('totalPayment', $totalPayment)
                ->with('totalSaldo', $totalInvoice -  $totalPayment)
                ->make(true);
            }
            return DataTables::of($data)->with('totalInvoice',0)->with('totalPayment',0)->make(true);
        }

        $vendors = Vendor::all();
        return view('payment_history.index', compact('vendors'));
    }

    public function import(Request $request)
    {
        Excel::import(new PaymentHistoryImport($request->vendor_id), $request->file('file'));

        return back();
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

    public function exportReport(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required',
        ]);

        $vendor = Vendor::find($request->vendor_id);
        return Excel::download(new PaymentHistoryExport($request->vendor_id, $request->dateStart, $request->dateEnd), 'Payment History ' . $vendor->name . '.xlsx');
    }

    public function updateColor(Request $request)
    {
        $data = Validator::make($request->all(), [
            'id' => 'required',
            'color' => 'required',
        ])->validate();
        DB::beginTransaction();

        try {

            $check = PaymentHistory::where('id', $request->id)->first();

            if(!$check){
                return response()->json([
                    'status' => 500,
                    'msg' => 'Data Tidak Ditemukan.'
                ]);
            }

            $checkColor = PaymentHistory::where('invoice_id', '!=', $check->invoice_id)->where('color', $request->color)->first();

            if($checkColor){
                return response()->json([
                    'status' => 500,
                    'msg' => 'Warna sudah ada.'
                ]);
            }

            if($check->invoice_id)
            {
                PaymentHistory::where('invoice_id', $check->invoice_id)->update([
                    'color' => $request->color
                ]);
            }

            PaymentHistory::where('id', $request->id)->update([
                'color' => $request->color
            ]);
            DB::commit();

            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Mengganti Warna.'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Mengganti Warna. ' . $exception->getLine() . ' ' . $exception->getMessage()
            ]);
        }
    }
}
