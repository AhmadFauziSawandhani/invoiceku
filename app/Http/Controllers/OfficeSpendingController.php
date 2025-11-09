<?php

namespace App\Http\Controllers;

use App\Exports\OfficeSpendingExport;
use App\Model\Asset;
use App\Model\Asset_detail;
use App\Model\FinancialRecap;
use App\Model\Logs;
use App\Model\OfficeSpending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class OfficeSpendingController extends Controller
{
    public function index(){
        return view('office_spending.index');
    }

    public function list(Request $request){
//        DB::statement(DB::raw('set @rownum=0'));
//        $query = OfficeSpending::select([
//            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
//            'id',
//            'spending_name',
//            'spending_date',
//            'amount',
//            DB::raw("SUM(amount) over(order by spending_date ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS `total_accumulation`"),
//            'spending_type'
//        ]);

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

        // $query = DB::select(
        //     DB::raw("
        //      SELECT t.id, t.spending_name, t.spending_date, t.amount, t.spending_type, t.created_name,
        //          (SELECT SUM(x.amount)
        //             FROM office_spendings x
        //             WHERE x.id <= t.id
        //             AND spending_date BETWEEN '$dateStart' AND '$dateEnd') AS cumulative_sum
        //      FROM office_spendings t
        //      WHERE spending_date BETWEEN '$dateStart' AND '$dateEnd'
        //      ORDER BY t.spending_date ASC
        // ")
        // );

        $query = OfficeSpending::orderBy('spending_date', 'asc')
                ->where('spending_date', '>=', $dateStart)->where('spending_date', '<=', $dateEnd);

        $datalist = $query->get();

        return DataTables::of($datalist)
            ->filter(function ($instance) use ($request) {
                if (!empty($request->search['value'])) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if (Str::contains(Str::lower($row['spending_name']), Str::lower($request->search['value']))) {
                            return true;
                        } else if (Str::contains(Str::lower($row['created_name']), Str::lower($request->search['value']))) {
                            return true;
                        } else if (Str::contains(Str::lower($row['spending_type']), Str::lower($request->search['value']))) {
                            return true;
                        }

                        return false;
                    });
            }
            })
            ->addColumn('action', function ($row) {
                $buttons = '';
            if (Auth::user()->role == 'admin') {
                $buttons .= '<a href="#" class="btn btn-danger mb-2 btnDelSpending " data-toggle="tooltip" data-placement="top" title="Hapus Pengeluaran" data-id="' . $row['id'] . '"><i class="fa fa-window-close"></i> Hapus Pengeluaran</a>
                                ';
            }

                return $buttons;

            })
            ->rawColumns(['action'])
            ->with('nominal_sum', function () use ($query, $request, $dateStart, $dateEnd) {
                if ($request->get('dateStart')) {
                    $query = $query->where('spending_date', '>=', $dateStart);
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('spending_date', '<=', $dateEnd);
                }


                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('spending_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('created_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('spending_type', 'like', '%' . $request->search['value'] . '%');
                    });
                }

                return $query->sum('amount');
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'spending_name' => ['required'],
            'spending_date' => ['required'],
            'amount' => ['required'],
            'spending_type' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $OfficeSpending = OfficeSpending::create([
                'spending_name' => strtoupper($input['spending_name']),
                'spending_date' => $input['spending_date'],
                'amount' => $input['amount'],
                'spending_type' => $input['spending_type'],
                'created_by' => Auth()->user()->uuid,
                'created_name' => Auth()->user()->full_name
            ]);

            $asset = Asset::where('asset_date', $input['spending_date'])->first();
            if ($asset) {
                $asset->spending_amount += $input['amount'];

                if ($input['spending_type'] == OfficeSpending::TYPE_OPERATIONAL) {
                    $asset->operational -= $input['amount'];
                } elseif ($input['spending_type'] == OfficeSpending::TYPE_TURNOVER) {
                    $asset->turnover -= $input['amount'];
                } elseif ($input['spending_type'] == OfficeSpending::TYPE_VENDOR) {
                    $asset->vendor -= $input['amount'];
                } elseif ($input['spending_type'] == OfficeSpending::TYPE_SALARY) {
                    $asset->salary_account -= $input['amount'];
                } elseif ($input['spending_type'] == OfficeSpending::TYPE_SAVING) {
                    $asset->saving_account -= $input['amount'];
                }

                $asset->save();
            } else {
                $asset = Asset::create([
                    'asset_date' => $input['spending_date'],
                    'turnover' => 0,
                    'salary_account' => 0,
                    'saving_account' => 0,
                    'operational' => 0,
                    'vendor' => 0,
                    'religious_meal' => 0,
                    'spending_amount' => $input['amount']
                ]);
            }

            Asset_detail::create([
                'asset_id' => $asset->id,
                'asset_date' => $input['spending_date'],
                'transaction_id' => $OfficeSpending->id,
                'transaction_type' => 2,
                'amount' => $input['amount'],
                'spending_type' => $input['spending_type'],
                'source' => 'Office Spending'
            ]);

            $financial = FinancialRecap::first();
            if ($input['spending_type'] == OfficeSpending::TYPE_OPERATIONAL) {
                $financial->operational -= $input['amount'];
            } elseif ($input['spending_type'] == OfficeSpending::TYPE_TURNOVER) {
                $financial->turnover -= $input['amount'];
                $financial->global_turnover -= $input['amount'];
            } elseif ($input['spending_type'] == OfficeSpending::TYPE_SALARY) {
                $financial->salary -= $input['amount'];
            } elseif ($input['spending_type'] == OfficeSpending::TYPE_SAVING) {
                $financial->saving -= $input['amount'];
            } elseif ($input['spending_type'] == OfficeSpending::TYPE_VENDOR) {
                $financial->vendor -= $input['amount'];
            }

            $financial->save();

            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' =>'Berhasil menyimpan data pengiriman.'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal menyimpan data pengiriman. ' . $exception->getLine() . ' ' . $exception->getMessage()
            ]);
        }
    }

    public function export_excel(Request $request)
    {
        return Excel::download(new OfficeSpendingExport($request->all()), 'Pengeluaran_kantor.xlsx');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'office_spending_id' => ['required'],
        ]);

        DB::beginTransaction();
        try {

            $spending = OfficeSpending::find($request->office_spending_id);

            $asset = Asset::where('asset_date', $spending->spending_date)->first();
            if ($asset) {
                $asset->spending_amount -= $spending->amount;

                if ($spending->spending_type == OfficeSpending::TYPE_OPERATIONAL) {
                    $asset->operational += $spending->amount;
                } elseif ($spending->spending_type == OfficeSpending::TYPE_TURNOVER) {
                    $asset->turnover += $spending->amount;
                } elseif ($spending->spending_type == OfficeSpending::TYPE_VENDOR) {
                    $asset->vendor += $spending->amount;
                } elseif ($spending->spending_type == OfficeSpending::TYPE_SALARY) {
                    $asset->salary_account += $spending->amount;;
                } elseif ($spending->spending_type == OfficeSpending::TYPE_SAVING) {
                    $asset->saving_account += $spending->amount;;
                }

                $asset->save();
            }


            Asset_detail::where('transaction_id', $spending->id)->where('asset_id', $asset->id)->where('transaction_type',2)->delete();

            $financial = FinancialRecap::first();
            if ($spending->spending_type == OfficeSpending::TYPE_OPERATIONAL) {
                $financial->operational += $spending->amount;
            } elseif ($spending->spending_type == OfficeSpending::TYPE_TURNOVER) {
                $financial->turnover += $spending->amount;
                $financial->global_turnover += $spending->amount;
            } elseif ($spending->spending_type == OfficeSpending::TYPE_SALARY) {
                $financial->salary += $spending->amount;
            } elseif ($spending->spending_type == OfficeSpending::TYPE_SAVING) {
                $financial->saving += $spending->amount;
            } elseif ($spending->spending_type == OfficeSpending::TYPE_VENDOR) {
                $financial->vendor += $spending->amount;
            }

            $financial->save();

            $spending->delete();

            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil hapus pengeluaran kantor'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal menghapus data pengeluaran kantor. ' . $exception->getLine() . ' ' . $exception->getMessage()
            ]);
        }
    }
}
