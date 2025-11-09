<?php

namespace App\Http\Controllers;

use App\Model\Order;
use App\Model\OrderItem;
use App\Model\Customer;
use App\Model\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('customer')->orderBy('id','desc')->get();
        return view('orders.index', compact('orders'));
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
        return view('orders.create', compact('customers','products'));
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
            'customer_id' => 'required',
            'order_date' => 'required',
            'products.*.id' => 'required',
            'products.*.quantity' => 'required|numeric|min:1'
        ]);

        $totalAmount = 0;

        // Hitung total amount
        foreach($request->products as $prod){
            $totalAmount += $prod['price'] * $prod['quantity'];
        }

        // Simpan order
        $order = Order::create([
            'order_number' => 'ORD-'.date('YmdHis'),
            'customer_id' => $request->customer_id,
            'order_date' => $request->order_date,
            'status' => 'PENDING',
            'total_amount' => $totalAmount
        ]);

        // Simpan order items
        foreach($request->products as $prod){
            $order->items()->create([
                'product_id' => $prod['id'],
                'quantity' => $prod['quantity'],
                'price' => $prod['price']
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'items.product'])->find($id);

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order tidak ditemukan!');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Optional: update status order
     */
    
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Status order diperbarui!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
