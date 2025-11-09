<?php

namespace App\Http\Controllers;

use App\Model\VendorAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            'account_name' => 'required',
            'account_number' => 'required',
            'account_bank' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {

            VendorAccount::create($data);


            DB::commit();

            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Menambahkan Rekening.'
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
            'account_name' => 'required',
            'account_number' => 'required',
            'account_bank' => 'required',
        ])->validate();

        $account  = VendorAccount::find($id);

        $account->update($data);

        return response()->json([
            'status' => 200,
            'msg' => 'Berhasil Mengubah Rekening.'
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
        $account  = VendorAccount::find($id);

        $account->delete();

        return response()->json([
            'status' => 200,
            'msg' => 'Berhasil Menghapus Rekening.'
        ]);
    }
}
