<?php

namespace App\Http\Controllers;

use App\Exports\VendorSpendingExport;
use App\Model\Asset;
use App\Model\Asset_detail;
use App\Model\FinancialRecap;
use App\Model\OfficeSpending;
use App\Model\PaymentHistory;
use App\Model\Vendor;
use App\Model\VendorAccount;
use App\Model\VendorInvoice;
use App\Model\VendorSpending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class VendorSpendingController extends Controller
{
    public function index(){
        $vendors = Vendor::all();

        return view('vendor_spending.index',compact('vendors'));
    }

    public function getAccountVendor(Request $request){
        $data = VendorAccount::where('vendor_id', $request->vendor_id)->get();
        return response()->json($data);
    }
    public function getInvoiceVendor(Request $request)
    {
        $data = VendorInvoice::where('vendor_id', $request->vendor_id)->get();
        return response()->json($data);
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
        $query = VendorSpending::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'invoice_number',
            'vendor_name',
            'amount',
            'spending_type',
            'spending_date'
        ])->orderBy('spending_date', 'asc')->where('spending_date', '>=', $dateStart)->where('spending_date', '<=', $dateEnd);

        $datalist = $query->get();

        return DataTables::of($datalist)
            ->filter(function ($instance) use ($request) {
                if (!empty($request->search['value'])) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if (Str::contains(Str::lower($row['invoice_number']), Str::lower($request->search['value']))) {
                            return true;
                        } else if (Str::contains(Str::lower($row['vendor_name']), Str::lower($request->search['value']))) {
                            return true;
                        } else if (Str::contains(Str::lower($row['spending_type']), Str::lower($request->search['value']))) {
                            return true;
                        }

                        return false;
                    });
                }
            })
            ->with('nominal_sum', function () use ($query, $request, $dateStart, $dateEnd) {
                if ($request->get('dateStart')) {
                    $query = $query->where('spending_date', '>=', $dateStart);
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('spending_date', '<=', $dateEnd);
                }


                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('invoice_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('vendor_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('spending_type', 'like', '%' . $request->search['value'] . '%');
                    });
                }

                return $query->sum('amount');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'invoice_number' => ['required'],
            'vendor_id' => ['required'],
            'amount' => ['required'],
            'spending_type' => ['required'],
            'spending_date' => ['required', 'date'],
            'account_vendor' => ['nullable'],
            'remark' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {
            $vendor = Vendor::find($input['vendor_id']);
            $invoice = VendorInvoice::where('id', $input['invoice_number'])->first();
            $invoice->remaining_amount = $invoice->remaining_amount ? $invoice->remaining_amount - $input['amount'] : 0;
            $invoice->save();

            if($request->account_vendor)
            {
                $account_vendor = VendorAccount::find($request->account_vendor);
            }

            $VendorSpending = VendorSpending::create([
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'vendor_id' => $vendor->id,
                'vendor_name' => strtoupper($vendor->name),
                'amount' => $input['amount'],
                'spending_type' => $input['spending_type'],
                'spending_date' => $input['spending_date'],
                'account_name' => $account_vendor ? $account_vendor->account_name : null,
                'account_number' => $account_vendor ? $account_vendor->account_number : null,
                'account_bank' => $account_vendor ? $account_vendor->account_bank : null,
                'created_by' => Auth()->user()->uuid,
                'created_name' => Auth()->user()->full_name,
            ]);

            $VendorSpending->payment_history()->create([
                'vendor_id' => $vendor->id,
                'date' => $input['spending_date'],
                'invoice_no' => $invoice->invoice_number,
                'amount' => $input['amount'],
                'type' => PaymentHistory::TYPE_PAYMENT,
                'remark' => $input['remark'],
                'account_name' => $account_vendor ? $account_vendor->account_name : null,
                'account_number' => $account_vendor ? $account_vendor->account_number : null,
                'account_bank' => $account_vendor ? $account_vendor->account_bank : null,
                'color' => $invoice->payment_history->color ?? null,
            ]);

            $asset = Asset::where('asset_date', $input['spending_date'])->first();

            if ($asset) {
                $asset->spending_amount += $input['amount'];

                if ($input['spending_type'] == VendorSpending::TYPE_OPERATIONAL) {
                    $asset->operational -= $input['amount'];
                } elseif ($input['spending_type'] == VendorSpending::TYPE_TURNOVER) {
                    $asset->turnover -= $input['amount'];
                } elseif ($input['spending_type'] == VendorSpending::TYPE_VENDOR) {
                    $asset->vendor -= $input['amount'];
                } elseif ($input['spending_type'] == VendorSpending::TYPE_SALARY) {
                    $asset->salary_account -= $input['amount'];
                } elseif ($input['spending_type'] == VendorSpending::TYPE_SAVING) {
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
                'transaction_id' => $VendorSpending->id,
                'transaction_type' => 3,
                'invoice_number' => $invoice->invoice_number,
                'amount' => $input['amount'],
                'spending_type' => $input['spending_type'],
                'source' => 'Vendor Spending'
            ]);

            $financial = FinancialRecap::first();
            if ($input['spending_type'] == VendorSpending::TYPE_OPERATIONAL) {
                $financial->operational -= $input['amount'];
            } elseif ($input['spending_type'] == VendorSpending::TYPE_TURNOVER) {
                $financial->turnover -= $input['amount'];
                $financial->global_turnover -= $input['amount'];
            } elseif ($input['spending_type'] == VendorSpending::TYPE_SALARY) {
                $financial->salary -= $input['amount'];
            } elseif ($input['spending_type'] == VendorSpending::TYPE_SAVING) {
                $financial->saving -= $input['amount'];
            } elseif ($input['spending_type'] == VendorSpending::TYPE_VENDOR) {
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
        return Excel::download(new VendorSpendingExport($request->all()), 'Pengeluaran_vendor.xlsx');
    }

    public function updateInvoiceIds()
    {
        $data = VendorSpending::has('payment_history')->where('invoice_id', '!=', null)->get();

        foreach ($data as $key) {
            $key->payment_history->invoice_id = $key->invoice_id;
            $key->payment_history->save();
        }

        $inv = VendorInvoice::has('payment_history')->get();
        foreach ($inv as $invs) {
            $invs->payment_history->invoice_id = $invs->id;
            $invs->payment_history->save();
        }


        $invoices = VendorInvoice::has('payments')->get();
        //update remaining amount
        foreach ($invoices as $invoice) {
            $invoice->remaining_amount = $invoice->amount - $invoice->payments->sum('amount');
            $invoice->save();
        }

        //update omset
        $invoices = VendorInvoice::whereNotNull('omset')->get();
        foreach ($invoices as $invoice) {
            $invoice->profit = $invoice->omset - $invoice->hpp;
            $invoice->save();
        }

        return 'true';
    }
}
