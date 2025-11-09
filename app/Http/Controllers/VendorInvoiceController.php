<?php

namespace App\Http\Controllers;

use App\Model\PaymentHistory;
use App\Model\Shipping;
use App\Model\Vendor;
use App\Model\VendorInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class VendorInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $query  = VendorInvoice::with(['shippings', 'shippings.shipping']);

            if (request('vendor_id')) {
                $query = $query->where('vendor_id', request('vendor_id'));
            }

            if (request('invoice_type')) {
                if(request('invoice_type') == 'finish'){
                    $query = $query->where('remaining_amount', 0);
                }

                if(request('invoice_type') == 'unfinished'){
                    $query = $query->where('remaining_amount', '>', 0);
                }
            }

            if (request('dateStart') && request('dateEnd')) {
                $query->whereBetween('date', [request('dateStart'), request('dateEnd')]);
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

        return view('vendor_invoice.index', $d);
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
            'vendor_id' => 'required',
            'invoice_number' => 'required',
            'date' => 'required',
            'due_date' => 'required',
            'remark' => 'nullable',
            'amount' => 'required',
            'shippings' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {

            $vendor = Vendor::find($data['vendor_id']);

            $data['vendor_name'] = $vendor->name;
            $data['vendor_id'] = $vendor->id;
            $data['created_by'] = Auth::user()->id;
            $data['created_name'] = Auth::user()->full_name;
            $data['remaining_amount'] = $data['amount'];
            $data['hpp'] = $request->amount;
            $data['shipping_id'] = $request->shipping_id;
            $data['no_resi'] = $request->no_resi;
            $data['nama_cs'] = $request->nama_cs;
            $data['tujuan'] = $request->tujuan;
            $data['omset'] = $request->omset;
            $data['profit'] = $request->profit;


            $invoice = VendorInvoice::create($data);

            foreach ($data['shippings'] as $item) {
                $shipping = Shipping::find($item);
                $invoice->shippings()->create([
                    'shipping_id' => $shipping->id,
                    'invoice_id' => $invoice->id,
                    'receipt_number' => $shipping->receipt_number,
                    'shipping_name' => $shipping->shipping_name,
                    'destination' => $shipping->destination,
                    'total' => $shipping->total
                ]);
            }

            do {
                $color = $this->generateRandomHexColor();
            } while (PaymentHistory::where('color', $color)->exists());


            $invoice->payment_history()->create([
                'vendor_id' => $vendor->id,
                'date' => $data['date'],
                'invoice_no' => $data['invoice_number'],
                'amount' => $data['amount'],
                'remark' => $data['remark'],
                'due_date' => $data['due_date'],
                'type' => PaymentHistory::TYPE_INVOICE,
                'color' => $color
            ]);


            DB::commit();

            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Menambahkan Invoice.'
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = VendorInvoice::where('id', $id)->with('shippings', 'vendor')->first();

        return response()->json($data);
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
        $data = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'invoice_number' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'remark' => 'nullable',
            'shippings' => 'required',

        ])->validate();

        $invoice = VendorInvoice::find($id);
        $vendor = Vendor::find($data['vendor_id']);
        $data['vendor_name'] = $vendor->name;
        $data['remaining_amount'] = $data['amount'];
        $data['created_by'] = Auth::user()->id;
        $data['hpp'] = $request->amount;
        $data['shipping_id'] = $request->shipping_id;
        $data['no_resi'] = $request->no_resi;
        $data['nama_cs'] = $request->nama_cs;
        $data['tujuan'] = $request->tujuan;
        $data['omset'] = $request->omset;
        $data['profit'] = $request->profit;

        $invoice->update($data);
        if (count($data['shippings']) > 0) {
            $invoice->shippings()->delete();
            foreach ($data['shippings'] as $item) {
                $shipping = Shipping::find($item);
                $invoice->shippings()->create([
                    'shipping_id' => $shipping->id,
                    'invoice_id' => $invoice->id,
                    'receipt_number' => $shipping->receipt_number,
                    'shipping_name' => $shipping->shipping_name,
                    'destination' => $shipping->destination,
                    'total' => $shipping->total
                ]);
            }
        }





        return response()->json([
            'status' => 200,
            'msg' => 'Berhasil Mengubah Invoice.'
        ]);
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

    function generateRandomHexColor()
    {
        // Membuat nilai acak untuk setiap komponen warna (red, green, blue)
        $red = dechex(mt_rand(0, 255));
        $green = dechex(mt_rand(0, 255));
        $blue = dechex(mt_rand(0, 255));

        // Pastikan setiap komponen memiliki dua digit
        if (strlen($red) < 2) {
            $red = '0' . $red;
        }
        if (strlen($green) < 2) {
            $green = '0' . $green;
        }
        if (strlen($blue) < 2) {
            $blue = '0' . $blue;
        }

        // Menggabungkan komponen-komponen tersebut menjadi satu nilai heksadesimal
        $randomColor = '#' . $red . $green . $blue;

        return $randomColor;
    }

    public function getCustomerInvoice(Request $request)
    {
        $search = $request->search;
        $data = [];

        if (strlen($search) > 2) {
            $data = Shipping::doesntHave('vendor_invoice')->where(function ($query) use ($search) {
                $query->where('receipt_number', 'like', '%' . $search . '%')
                    ->orWhere('invoice_number', 'like', '%' . $search . '%');
            })->get();
        }

        return $data;
    }

    public function updateProfitVendor(Request $request){
        $inv = VendorInvoice::whereNotNull('omset')->whereHas('shippings')->with(['shippings', 'shippings.shipping'])->get();
        foreach($inv as $iinvoice){
            $omset = 0;
            foreach($iinvoice->shippings as $shipping){
                $shipping->update([
                    'total' => $shipping->shipping->sub_total + $shipping->shipping->ppn
                ]);

                $omset += $shipping->shipping->sub_total + $shipping->shipping->ppn;

            }

            $iinvoice->update([
                'omset' => $omset,
                'profit' => $omset - $iinvoice->amount
            ]);

        }

        return response()->json(['status' => 200]);
    }

}
