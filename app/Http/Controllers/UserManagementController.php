<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(User::all())->make(true);
        }
        return view('user_management.index');
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
            'full_name' => 'required',
            'email' => 'required | email',
            'role' => 'required',
            'password' => 'required',
        ])->validate();

        // dd($data);

        $user = User::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'uuid' => Str::uuid()
        ]);

        return response()->json([
            'status' => 200,
            'msg' => 'Berhasil Menambahkan User Baru.'
        ]);
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
        $data = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required | email',
            'role' => 'required',
            'password' => 'nullable',
        ])->validate();

        $user = User::find($id);
        $user->update([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password'] ?? $user->password),
        ]);

        return response()->json([
            'status' => 200,
            'msg' => 'Berhasil Mengubah User.'
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
}
