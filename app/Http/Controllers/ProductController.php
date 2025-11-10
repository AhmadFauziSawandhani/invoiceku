<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Model\Product;
use App\Model\ProductPrice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Ambil data dari tabel products
            $products = Product::select(['id', 'name', 'price', 'unit', 'created_at', 'updated_at']);
            return DataTables::of($products)
                ->addIndexColumn()
                 ->editColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($row) {
                    $btn = '
                        <a href="' . route('master.products.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                        <button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger btn-delete">Delete</button>
                    ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.product.index');
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
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
        ]);

        // Simpan ke tabel products
        $product = Product::create([
            'name'  => $request->name,
            'price' => $request->price,
            'unit'  => $request->unit,
        ]);

        // Simpan ke tabel product_prices
        ProductPrice::create([
            'product_id' => $product->id,
            'price'      => $product->price,
            'effective_date' => now(), // tambahkan kalau kamu ingin tahu sejak kapan harga berlaku
        ]);

        return response()->json([
            'status' => 200,
            'msg'    => 'Product created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric',
            'unit' => 'required|string',
        ]);

        $product = Product::findOrFail($id);
        $oldPrice = $product->price;
        $product->update([
            'name'  => $request->name,
            'price' => $request->price,
            'unit'  => $request->unit,
        ]);

        if ((float) $request->price !== (float) $oldPrice) {
            ProductPrice::create([
                'product_id'     => $product->id,
                'price'          => $product->price,
                'effective_date' => now(),
            ]);
        }

        return response()->json([
            'status' => 200,
            'msg' => 'Product updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json([
            'status' => 200,
            'msg' => 'Product deleted successfully.'
        ]);
    }
}
