<?php

namespace App\Http\Controllers;

use App\Model\PaymentHistory;
use App\Model\Vendor;
use App\Model\VendorAccount;
use App\Model\VendorInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->ajax()) {
            return DataTables::of(Vendor::with('accounts')->get())->make(true);
        }
        return view('master.vendor.index');
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
        $data = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'account_bank' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ])->validate();
        DB::beginTransaction();

        try {
            $vendor = Vendor::create($data);

            VendorAccount::create([
                'vendor_id' => $vendor->id,
                'account_bank' => $data['account_bank'],
                'account_name' => $data['account_name'],
                'account_number' => $data['account_number'],
            ]);

            DB::commit();

            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Menambahkan vendor.'
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Menambahkan vendor. ' . $exception->getLine() . ' ' . $exception->getMessage()
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendor = Vendor::find($id);
        if (request()->ajax()) {
            $data = [];
                $query = PaymentHistory::where('vendor_id', $id);
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

        return view('master.vendor.show' , compact('vendor'));
    }

    public function invoices($id){
        if (request()->ajax()) {
            return DataTables::of(VendorInvoice::where('vendor_id', $id)->get())->make(true);
        }
        $d['vendor'] = Vendor::find($id);
        $d['totalInvoice'] = PaymentHistory::where('vendor_id', $id)->where('type', PaymentHistory::TYPE_INVOICE)->sum('amount');
        $d['totalInvoiceMonth'] = VendorInvoice::where('vendor_id', $id)->whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('amount');
        $d['qtyInvoiceMonth'] = VendorInvoice::where('vendor_id', $id)->whereMonth('date', date('m'))->whereYear('date', date('Y'))->count();
        $d['payment'] = PaymentHistory::where('vendor_id', $id)->where('type', PaymentHistory::TYPE_PAYMENT)->sum('amount');
        $d['paymentMonth'] = PaymentHistory::where('vendor_id', $id)->whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('amount');
        $d['saldoTotal'] = $d['totalInvoice'] - $d['payment'];
        $d['saldoMonth'] = $d['totalInvoiceMonth'] - $d['paymentMonth'];
        $d['due_date'] = VendorInvoice::where('vendor_id', $id)->where('due_date', '<', date('Y-m-d'))->count();

        return view('master.vendor.invoice-list', $d);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


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
        $vendor = Vendor::find($id);

        $data = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'account_bank' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ])->validate();
        DB::beginTransaction();
        try {

            $vendor->update($data);

            $vendorAccount = VendorAccount::where('vendor_id', $id)->first();

            $vendorAccount->update([
                'account_bank' => $data['account_bank'],
                'account_name' => $data['account_name'],
                'account_number' => $data['account_number'],
            ]);

            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Mengubah vendor.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Terjadi kesalahan. Silahkan coba lagi.' . $th->getMessage()
            ], 500);
        }
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
