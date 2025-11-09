<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(){
        if(request()->ajax()){
            return DataTables::of(Customer::all())->make(true);
        }
        return view('master.customer.index');
    }

    public function store(Request $request){
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|numeric',
            'email' => 'required | email',
        ]);

        $cus = Customer::create($request->only('name', 'phone'));

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

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        $data = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ])->validate();

        $customer->update($data);

        return response()->json([
            'status' => 200,
            'msg' => 'Berhasil Mengubah customer.'
        ]);
    }
}
