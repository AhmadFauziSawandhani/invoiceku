<?php

namespace App\Http\Controllers;

use App\Model\Complaint;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ComplainController extends Controller
{
    public function index(){

        if (request()->ajax()) {
            $query = Complaint::query()->latest();
            return DataTables::of($query)->make(true);
        }
        $keluhanHariIni = Complaint::whereDate('created_at', date('Y-m-d'))->count();
        $keluhanPending = Complaint::where('status', 'Pending')->count();
        $keluhanProgress = Complaint::where('status', 'On Progress')->count();
        $keluhanSelesai = Complaint::where('status', 'Selesai')->count();
        return view('customer-complain.index', compact('keluhanHariIni', 'keluhanPending', 'keluhanProgress', 'keluhanSelesai'));
    }

    public function show($id){
        $data = Complaint::find($id);
        return view('customer-complain.show',compact('data'));
    }

    public function updateStatus(Request $request, $id){
        $data = Complaint::find($id);
        $data->status = $request->status;
        $data->save();
        return redirect()->back();
    }
}
