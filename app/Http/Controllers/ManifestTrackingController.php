<?php

namespace App\Http\Controllers;

use App\Model\Manifest;
use App\Model\ManifestTracking;
use App\Model\ManifestTrackingStatus;
use App\Model\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ManifestTrackingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Manifest::has('tracking')
                ->with('tracking', 'lastTracking', 'product')
                ->when($request->status, function ($query) use ($request) {
                    $query->whereHas('tracking', function ($query) use ($request) {
                        $query->where('status', $request->status);
                    })->latest();
                })
                ->when($request->vendor_id, function ($query) use ($request) {
                    $query->whereHas('tracking', function ($query) use ($request) {
                        $query->where('vendor_id', $request->vendor_id);
                    });
                })
                ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                    $query->whereBetween('date_manifest', [$request->start_date, $request->end_date]);
                })->get();

            return DataTables::of($data)->make(true);
        }
        $vendors = Vendor::all();
        $statuses  = ManifestTrackingStatus::all();
        return view('tracking.index', compact('vendors', 'statuses'));
    }

    public function create()
    {
        $statuses  = ManifestTrackingStatus::all();
        return view('tracking.create', compact('statuses'));
    }

    public function getManifestdosntHaveTracking()
    {
        $data = Manifest::has('tracking')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'manifest_id' => 'required|array',
            // 'vendor_type' => 'required',
            'status' => 'required',
            'note' => 'required',
            // 'tracking_date' => 'required',
            // 'etd' => 'required',
            // 'eta' => 'required',
        ]);


        DB::beginTransaction();
        try {
            //update manifest
            foreach ($request->manifest_id as $manifest_id) {
                $manifest = Manifest::find($manifest_id['id']);
                $manifest->update([
                    'etd' => $manifest_id['etd'],
                    'eta' => $manifest_id['eta'],
                ]);



                //insert tracking
                $manifest->tracking()->create([
                    'vendor_type' => $manifest->lastTracking ? $manifest->lastTracking->vendor_type : null,
                    'vendor_id' => $manifest->lastTracking ? $manifest->lastTracking->vendor_id : null,
                    'vendor_name' => $manifest->lastTracking ? $manifest->lastTracking->vendor_name : null,
                    'resi_vendor' => $manifest->lastTracking ? $manifest->lastTracking->resi_vendor : null,
                    'status' => $request->status,
                    'note' => $request->note,
                    'tracking_date' => $request->tracking_date,
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Membuat Tracking Manifest/Resi.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => $th->getMessage()
            ]);
        }
    }

    public function updateSelectedTracking(Request $request)
    {
        $request->validate([
            'manifest_id' => 'required|array',
            'status' => 'required',
            'note' => 'required',
            'tracking_date' => 'required',
        ]);

        DB::beginTransaction();
        try {
            //update manifest
            foreach ($request->manifest_id as $manifest_id) {
                $manifest = Manifest::find($manifest_id);

                //insert tracking
                $vendor = null;
                if ($request->vendor_id) {
                    $vendor = Vendor::where('id', $request->vendor_id)->first();
                }
                if ($request->hasFile('proof')) {
                    $file = $request->file('proof');
                    $path = $file->store('tracking_proof', 'public');
                }

                $manifest->tracking()->create([
                    'vendor_type' => $request->vendor_type,
                    'vendor_id' => $vendor ? $vendor->id : null,
                    'vendor_name' => $vendor ? $vendor->name : null,
                    'resi_vendor' => $request->resi_vendor,
                    'status' => $request->status,
                    'note' => $request->note,
                    'tracking_date' => $request->tracking_date,
                    'courier_name' => $request->courier_name,
                    'courier_phone' => $request->courier_phone,
                    'proof' => $path ?? null,
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Update Tracking Manifest/Resi.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Update Tracking Manifest/Resi.' . $th->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'manifest_tracking_id' => 'required',
            'status' => 'required',
            'note' => 'required',
            'tracking_date' => 'required',
        ]);


        DB::beginTransaction();
        try {

            $manifest_tracking = ManifestTracking::find($request->manifest_tracking_id);
            $vendor = null;
            if ($request->vendor_id) {
                $vendor = Vendor::where('id', $request->vendor_id)->first();
            }
            $manifest_tracking->update([
                'status' => $request->status,
                'note' => $request->note,
                'tracking_date' => $request->tracking_date,
                'vendor_type' => $request->vendor_type,
                'vendor_id' => $vendor ? $vendor->id : null,
                'vendor_name' => $vendor ? $vendor->name : null,
            ]);
            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Update Tracking Manifest/Resi.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Update Tracking Manifest/Resi.'
            ]);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'manifest_tracking_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $manifest_tracking = ManifestTracking::find($request->manifest_tracking_id);
            $manifest_tracking->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Hapus Tracking Manifest/Resi.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Hapus Tracking Manifest/Resi.'
            ]);
        }
    }


    public function getStatus()
    {
        $data = ManifestTrackingStatus::all();
        return response()->json($data);
    }
}
