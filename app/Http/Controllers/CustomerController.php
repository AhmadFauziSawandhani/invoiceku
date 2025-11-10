<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(){
        if (request()->ajax()) {
            $customers = Customer::with('user')->get();

            return DataTables::of($customers)
                ->addColumn('email', fn($row) => $row->user->email ?? '-')
                ->make(true);
        }

        return view('master.customer.index');
    }

    public function store(Request $request){
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'email' => 'required | email',
        ]);

        $cus = Customer::create($request->only('name', 'phone', 'address'));

        $role = "admin";
        $encoded = "cGFzc3dvcmQxMjM=";
        $pass = base64_decode($encoded);
        $ref_type = "customers";
        $ref_id = $cus->id;

        $user = User::create([
            'full_name' => $request['name'],
            'email' => $request['email'],
            'role' => $role,
            'password' => Hash::make($pass),
            'uuid' => Str::uuid(),
            'ref_type' => $ref_type,
            'ref_id' => $ref_id
        ]);

        return response()->json([
            'status' => 200,
            'msg' => 'Customer created successfully.'
        ]);
    }

    public function getCustomer()
    {
        $customer = Customer::all();
        return $customer;
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $customer = Customer::with('user')->findOrFail($id);

            // Update tabel customers
            $customer->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Update tabel users jika relasi ada
            if ($customer->user) {
                $customer->user->update([
                    'full_name' => $request->name,
                    'email' => $request->email,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil mengubah data customer dan user.'
            ]);

        } catch (\Throwable $e) { // ✅ ganti $th jadi $e agar konsisten
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'msg' => 'Gagal mengubah customer: ' . $e->getMessage()
            ]);
        }
    }
}
