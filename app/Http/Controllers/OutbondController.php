<?php

namespace App\Http\Controllers;

use App\Model\Manifest;
use App\Model\ManifestTracking;
use App\Model\ManifestTrackingStatus;
use App\Model\Outbond;
use App\Model\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf as PDF;
use Yajra\DataTables\Facades\DataTables;

class OutbondController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Outbond::with('manifest','createdBy')->latest()
                           ->when($request->start_date && $request->end_date, function($query) use ($request) {
                               $query->whereBetween('date_outbond', [$request->start_date, $request->end_date]);
                           })->get();
            return DataTables::of($data)->make(true);
        }

        $vendors = Vendor::all();
        return view('outbond.index', compact('vendors'));

    }

    public function create()
    {
        $statuses  = ManifestTrackingStatus::all();
        return view('outbond.create', compact('statuses'));
    }

    public function edit($id){
        $outbond = Outbond::with('manifest')->find($id);
        $statuses  = ManifestTrackingStatus::all();
        return view('outbond.edit', compact('outbond', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_outbond' => 'required',
            'note' => 'nullable',
            'status' => 'nullable',
            'total_colly' => 'nullable',
            'total_weight' => 'nullable',
            'total_volume' => 'nullable',
            'total_volume_m3' => 'nullable',
            'manifest' => 'required|array',
            'manifest.*.id' => 'required',
            'manifest.*.note' => 'nullable',
            'warehouse_exit_date' => 'required',
            'driver' => 'required',
            'acknowledge' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $outbond = Outbond::create([
                'created_by' => auth()->user()->id,
                'date_outbond' => $request->date_outbond,
                'note' => $request->note,
                'status' => $request->status,
                'total_colly' => $request->total_colly,
                'total_weight' => $request->total_weight,
                'total_volume' => $request->total_volume,
                'total_volume_m3' => $request->total_volume_m3,
                'driver' => $request->driver,
                'acknowledge' => $request->acknowledge,
                'warehouse_exit_date' => $request->warehouse_exit_date,

            ]);

            foreach ($request->manifest as $man) {
                $manifest = Manifest::find($man['id']);
                $vendor = Vendor::where('id', $man['vendor_id'])->first();
                $manifest->update([
                    'vendor_id' => $vendor ? $vendor->id : null,
                    'vendor_name' => $vendor ? $vendor->name : null,
                    'vendor_type' => $man['vendor_type'],
                    'outbond_id' => $outbond->id,
                    'note' => $man['note'],
                    'warehouse_exit_date' => $request->warehouse_exit_date,
                ]);
                $manifest->tracking()->create([
                    'vendor_id' => $vendor ? $vendor->id : null,
                    'vendor_name' => $vendor ? $vendor->name : null,
                    'vendor_type' => $man['vendor_type'],
                    'resi_vendor' => $man['resi_vendor'],
                    'status' => $man['status'],
                    'note' => $man['note'],
                    'tracking_date' => $request->date_outbond,
                    'is_outbond' => 1,
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'data' => $outbond,
                'msg' => 'Berhasil Tambah Outbond.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Tambah Outbond.'
            ]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'outbond_id' => 'required',
            'note' => 'nullable',
            'status' => 'nullable',
            'total_colly' => 'nullable',
            'total_weight' => 'nullable',
            'total_volume' => 'nullable',
            'total_volume_m3' => 'nullable',
            'manifest' => 'required|array',
            'manifest.*.id' => 'required',
            'manifest.*.note' => 'nullable',
            'warehouse_exit_date' => 'required',
            'driver' => 'required',
            'acknowledge' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $outbond = Outbond::find($request->outbond_id);
            $outbond->update([
                'date_outbond' => $request->date_outbond,
                'note' => $request->note,
                'status' => $request->status,
                'total_colly' => $request->total_colly,
                'total_weight' => $request->total_weight,
                'total_volume' => $request->total_volume,
                'total_volume_m3' => $request->total_volume_m3,
                'driver' => $request->driver,
                'acknowledge' => $request->acknowledge,
                'warehouse_exit_date' => $request->warehouse_exit_date,
            ]);


            $oldManifest = Manifest::where('outbond_id', $request->outbond_id);

            foreach ($oldManifest as $old) {
                ManifestTracking::where('manifest_id', $old->id)->where('is_outbond', 1)->delete();
            };

            Manifest::where('outbond_id', $request->outbond_id)->update([
                'outbond_id' => null
            ]);

            foreach ($request->manifest as $man) {
                $manifest = Manifest::find($man['id']);
                $vendor = Vendor::where('id', $man['vendor_id'])->first();
                $manifest->update([
                    'vendor_id' => $vendor ? $vendor->id : null,
                    'vendor_name' => $vendor ? $vendor->name : null,
                    'vendor_type' => $man['vendor_type'],
                    'outbond_id' => $outbond->id,
                    'note' => $man['note'],
                    'warehouse_exit_date' => $request->warehouse_exit_date,
                ]);
                $manifest->tracking()->create([
                    'vendor_id' => $vendor ? $vendor->id : null,
                    'vendor_name' => $vendor ? $vendor->name : null,
                    'vendor_type' => $man['vendor_type'],
                    'resi_vendor' => isset($man['resi_vendor']) ? $man['resi_vendor'] : null,
                    'status' => $man['status'],
                    'note' => $man['note'],
                    'tracking_date' => $request->date_outbond,
                    'is_outbond' => 1,
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Update Outbond.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Update Outbond.' . $th->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {

        $request->validate([
            'outbond_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $manifests = Manifest::where('outbond_id', $request->outbond_id)->get();

            foreach ($manifests as $manifest) {
                ManifestTracking::where('manifest_id', $manifest->id)->where('is_outbond', 1)->delete();
            };

            Manifest::where('outbond_id', $request->outbond_id)->update([
                'outbond_id' => null
            ]);


            $outbond = Outbond::find($request->outbond_id);
            $outbond->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil Hapus Outbond.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal Hapus Outbond.'
            ]);
        }
    }

    public function getManifest()
    {
        $manifest = Manifest::whereNull('outbond_id')->whereHas('shipping.shipping', function ($q) {
            $q->where('ready_packing', 1);
        })->get();

        return response()->json($manifest);
    }

    public function printOutbond($id){

        ini_set("pcre.backtrack_limit", "5000000");
        ini_set("memory_limit", "-1");


        try {
            $outbond = outbond::with('createdBy','manifest.product')->find($id);
            $document = new PDF([
                'mode' => 'utf-8',
                'format' => 'a4',
                'setAutoTopMargin' => 'stretch',
                'setAutoBottomMargin' => 'pad',
                'margin-header' => 0,
                'margin_top' => '10',
                'margin_left' => '10',
                'margin_right' => '10',
                'margin_bottom' => '10',
            ]);
            $document->showImageErrors = true;
            $document->imageVars['myvariable'] = file_get_contents(public_path('mas-express.png'));
            $documentFileName = "Manifest Outbond-" . $outbond->date_outbond .  ".pdf";

            $html = view('pdf.outbond', compact('outbond'));
            $document->img_dpi = 50;
            $document->writeHTML($html, \Mpdf\HTMLParserMode::DEFAULT_MODE);

            // Save PDF on your public storage
            $document->Output($documentFileName, "I");
        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            echo $e->getMessage();
        }
    }
}
