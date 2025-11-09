<?php

namespace App\Http\Controllers;

use App\Exports\AssetExport;
use App\Model\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    public function index(){
        return view('asset.index');
    }

    public function list(Request $request){
        if (!$request->get('dateStart')) {
            $request->merge([
                'dateStart' => '2023-11-01'
            ]);
        }

        if (!$request->get('dateEnd')) {
            $request->merge([
                'dateEnd' => date('Y-m-d')
            ]);
        }

        $dateStart = $request->get('dateStart');
        $dateEnd = $request->get('dateEnd');
        DB::statement(DB::raw('set @rownum=0'));
        $query = Asset::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'asset_date',
            'turnover',
            'spending_amount',
            DB::raw("(SELECT SUM(total) FROM shippings WHERE invoice_date = asset_date) AS `total_invoice`"),
            DB::raw("(SELECT SUM(total) FROM shippings WHERE invoice_date = asset_date AND invoice_date = CURDATE()) AS `total_invoice_current`"),
            'updated_at'
        ])->orderBy('asset_date', 'DESC')->where('asset_date', '>=', $dateStart)->where('asset_date', '<=', $dateEnd);

        // dd($query);

        $datalist = $query->get();

        return DataTables::of($datalist)
            ->addColumn('total_invoice', function(Asset $data){
                return $data->total_invoice;
            })
            ->addColumn('updated_at', function(Asset $data){
                return $data->updated_at;
            })
            ->with('turnover_sum', function () use ($query, $request, $dateStart, $dateEnd) {
                if ($request->get('dateStart')) {
                    $query = $query->where('asset_date', '>=', $dateStart);
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('asset_date', '<=', $dateEnd);
                }

                return $query->sum('turnover');
            })
            ->with('total_invoice_sum', function () use ($query, $request, $dateStart, $dateEnd) {
                if ($request->get('dateStart')) {
                    $query = $query->where('asset_date', '>=', $dateStart);
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('asset_date', '<=', $dateEnd);
                }


                return $query->sum(DB::raw("(SELECT SUM(total) FROM shippings WHERE invoice_date = asset_date AND payment_status = 1)"));
            })
            ->with('spending_amount_sum', function () use ($query, $request, $dateStart, $dateEnd) {
                if ($request->get('dateStart')) {
                    $query = $query->where('asset_date', '>=', $dateStart);
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('asset_date', '<=', $dateEnd);
                }

                return $query->sum('spending_amount');
            })
            ->make(true);
    }

    public function export_excel(Request $request)
    {
        return Excel::download(new AssetExport($request->all()), 'Aset.xlsx');
    }
}
