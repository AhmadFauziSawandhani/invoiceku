<?php

namespace App\Http\Controllers;

use PDF;
use App\Model\Invoice;
use App\Model\Customer;
use App\Model\Product;
use App\Model\InvoiceItem;
use App\MOdel\ProductPrice;
use App\Model\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.invoices.index');
    }

    public function getDatas()
    {
        $data = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->select(
                'invoices.id',
                'invoices.invoice_number',
                'invoices.invoice_date',
                'invoices.total_amount',
                'invoices.status',
                'customers.name as customer_name'
            )
            ->orderBy('invoices.id', 'desc');

        return DataTables::of($data)
            ->editColumn('invoice_date', function ($row) {
                return Carbon::parse($row->invoice_date)->format('Y-m-d');
            })
            ->editColumn('status', function ($row) {
                if(strtoupper($row->status) === 'PAID'){
                    return '<span class="badge badge-success">'.$row->status.'</span>';
                } elseif(strtoupper($row->status) === 'PENDING'){
                    return '<span class="badge badge-warning">'.$row->status.'</span>';
                } else {
                    return '<span class="badge badge-secondary">'.$row->status.'</span>';
                }
            })
            ->addColumn('aksi', function($row) {
                $editUrl = route('invoices.edit', $row->id);
                $detailUrl = route('invoices.show', $row->id);
                $deleteUrl = route('invoices.destroy', $row->id);
                return '
                    <a href="'.$detailUrl.'" class="btn btn-warning btn-sm">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="'.$editUrl.'" class="btn btn-primary btn-sm">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-sm btn-delete" data-url="'.$deleteUrl.'">
                        <i class="fa fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['aksi','status']) // biar HTML badge tampil
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $settings = Setting::all();
        return view('master.invoices.create', compact('customers', 'products', 'settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.price' => 'nullable|numeric|min:0',
            'products.*.buying_price' => 'nullable|numeric|min:0',
            'products.*.quantity' => 'required|numeric|min:0.1',
            'products.*.subtotal' => 'nullable|numeric|min:0',
        ]);

        $customers = Customer::find($request->customer_id);

        DB::beginTransaction();
        try {
            // 🔹 Simpan invoice utama
            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'customer_id' => $customers['id'],
                'invoice_date' => $request->invoice_date,
                'total_amount' => 0,
                'status' => $request->status,
            ]);

            $total = 0;
            $totalProfit = 0;

            // 🔹 Loop setiap produk di invoice
            foreach ($request->products as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                $buyingPrice = $item['buying_price'] ?? 0;
                $sellingPrice = $item['price'] ?? $product->price;
                $qty = $item['quantity'];
                $subtotal = $sellingPrice * $qty;

                // 🔹 Simpan detail item invoice
                InvoiceItem::create([
                    'invoice_id'   => $invoice->id,
                    'product_id'   => $item['product_id'],
                    'buying_price' => $buyingPrice,
                    'selling_price'=> $sellingPrice,
                    'quantity'     => $qty,
                    'subtotal'     => $subtotal,
                ]);

                // 🔹 Cek apakah harga jual berbeda dari master product
                if ((float)$product->price !== (float)$sellingPrice) {
                    // Update harga di tabel products
                    $product->update(['price' => $sellingPrice]);

                    // Simpan riwayat harga di tabel product_prices
                    ProductPrice::create([
                        'product_id'     => $product->id,
                        'price'          => $sellingPrice,
                        'effective_date' => now(),
                    ]);
                }

                $total += $subtotal;
                $totalProfit += ($sellingPrice - $buyingPrice) * $qty;
            }

            // 🔹 Update total invoice dan total profit
            $invoice->update([
                'total_amount' => $total,
                'profit'       => $totalProfit,
            ]);

            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dibuat!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->find($id);

        if (!$invoice) {
            return redirect()->route('invoices.index')->with('error', 'Invoice tidak ditemukan!');
        }

        return view('master.invoices.detail', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();

        return view('master.invoices.edit', compact('invoice', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'status' => 'required',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.buying_price' => 'nullable|numeric|min:0',
            'products.*.quantity' => 'required|numeric|min:0.1',
            'products.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::findOrFail($id);

            // Hitung total
            $totalAmount = array_sum(array_column($request->products, 'subtotal'));

            // Update invoice utama
            $invoice->update([
                'customer_id' => $request->customer_id,
                'invoice_date' => $request->invoice_date,
                'status' => $request->status,
                'total_amount' => $totalAmount,
            ]);

            // Hapus item lama → buat ulang
            $invoice->items()->delete();

            $totalProfit = 0;

            foreach ($request->products as $item) {
                $buyingPrice = $item['buying_price'] ?? 0;
                $sellingPrice = $item['price'] ?? 0;
                $qty = $item['quantity'];
                $subtotal = $item['subtotal'];

                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'buying_price' => $buyingPrice,
                    'selling_price' => $sellingPrice,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);

                $totalProfit += ($sellingPrice - $buyingPrice) * $qty;
            }

            $invoice->update(['profit' => $totalProfit]);

            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'Invoice berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update invoice: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);

        $pdf = PDF::loadView('master.invoices.print', compact('invoice'))
                ->setPaper('A4', 'portrait');

        return $pdf->stream('invoice-'.$invoice->invoice_number.'.pdf');
    }
    
    public function printjalan($id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
        
        $pdf = PDF::loadView('master.invoices.print_surat_jalan', compact('invoice'))
                ->setPaper('A4', 'portrait');

        return $pdf->stream('invoice-'.$invoice->invoice_number.'.pdf');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return response()->json(['status' => 404, 'msg' => 'Data tidak ditemukan.']);
        }

        try {
            $invoice->items()->delete();
            $invoice->delete();
            return response()->json(['status' => 200, 'msg' => 'Invoice berhasil dihapus.']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }
}
