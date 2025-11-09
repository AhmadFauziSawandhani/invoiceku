<?php

namespace App\Http\Controllers;

use App\Exports\ShippingExport;
use App\Helpers\GeneralHelpers;
use App\Model\Asset;
use App\Model\Asset_detail;
use App\Model\Customer;
use App\Model\FinancialRecap;
use App\Model\Logs;
use App\Model\Shipping;
use App\Model\ShippingDetail;
use App\Model\ShippingImage;
use App\Model\ShippingPayment;
use App\Model\ShippingBank;
use App\Model\Manifest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\In;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Mpdf\Mpdf as PDF;
use App\Model\District;

class ShippingController extends Controller
{
    public function index(Request $request)
    {
        return view('shipping.index');
    }

    public function show(Request $request)
    {
        $data = Shipping::find($request->shipping_id);

        if (isset($data)) {
            $data->details = ShippingDetail::where('shipping_id', $request->shipping_id)->get();
            $data->shipping_banks = ShippingBank::select('id', 'name_account', 'name_bank', 'number_account')->where('shipping_id', $request->shipping_id)->get();
            $data->images = ShippingImage::where('shipping_id', $request->shipping_id)->get();

            $payload['data'] = $data;
            return view('shipping.edit-invoice', compact('payload'));
        } else {
            return view('shipping.index');
        }
    }

    public function create(Request $request)
    {
        $dataManifest = explode(',', $request->manifest_ids);
        $manifest = Manifest::with('product')->whereIn('id', $dataManifest)->get();

        return view('shipping.create-invoice', compact('manifest'));
    }

    public function list(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $query = Shipping::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'invoice_number',
            'invoice_date',
            'invoice_type',
            'shipping_name',
            'phone_number',
            'receipt_number',
            'ready_packing',
            'ppn',
            'sub_total',
            'total',
            'remaining_payment',
            'down_payment',
            'payment_name',
            'payment_type',
            'payment_status',
            'payment_due_date',
            'destination',
            'debt_age',
            'is_verification',
            'sales_name',
        ])->whereNull('deleted_at')->orderBy('invoice_date', 'desc')->where(function ($query) {
            $query->whereYear('invoice_date', date('Y'))->orWhereYear('invoice_date', date('Y') - 1);
        });

        $datalist = $query->get();
        return DataTables::of($datalist)
            ->filter(function ($instance) use ($request) {
                if ($request->ajax()) {
                    if (!empty($request->get('dateStart'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return ($row['invoice_date'] >= $request->get('dateStart')) ? true : false;
                        });
                    }

                    if (!empty($request->get('dateEnd'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return ($row['invoice_date'] <= $request->get('dateEnd')) ? true : false;
                        });
                    }

                    if (!empty($request->get('filterBy'))) {
                        if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['payment_type'] == $request->get('filterBy')) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 3) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['is_verification'] == 1) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 4) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                $gap = (new GeneralHelpers())->calculate_day_between_two_date($row['payment_due_date'], date('Y-m-d'));
                                return ($row['payment_status'] == 1 && $gap <= 3) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 5) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['invoice_type'] == 1) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 6) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['invoice_type'] == 3) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 7) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['invoice_type'] == 2) ? true : false;
                            });
                        }
                    }

                    if (!empty($request->get('shipperPhoneNumber'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains($row['phone_number'], $request->get('shipperPhoneNumber'))) {
                                return true;
                            }

                            return false;
                        });
                    }
                    if (!empty($request->get('sales_name'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains($row['sales_name'], $request->get('sales_name'))) {
                                return true;
                            }

                            return false;
                        });
                    }

                    if (!empty($request->search['value'])) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['invoice_number']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['shipping_name']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['destination']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['receipt_number']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['invoice_date']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['sales_name']), Str::lower($request->search['value']))) {
                                return true;
                            }

                            return false;
                        });
                    }
                }
            })
            ->addColumn('action', function (Shipping $data) {
                $buttons = '<div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <div class="dropdown-menu">
                            ';

                $day = (new GeneralHelpers())->calculate_day_between_two_date($data['payment_due_date'], date('Y-m-d'));

                if (($data['payment_status'] == 1)) {

                    $buttons .= '<a href="#" class="bg-danger mb-2 dropdown-item btn-notif" data-id="' . $data['id'] . '"><i class="fa fa-bell"></i> Kirim Reminder Pembayaran</a>';
                }


                $images = ShippingImage::where('shipping_id', $data['id'])->get()->toArray();

                $buttons .= '
                    <a class="bg-info mb-2 btn-print-invoice dropdown-item" data-toggle="tooltip" data-placement="top" title="Cetak Invoice"  href="' . route('shipping.print-invoice', $data->id) . '" target="_blank" data-id="' . $data['id'] . '"><i class="fa fa-print"></i> Print Invoice</a>
                    <button class="btnImgDt mb-2 dropdown-item" data-toggle="tooltip" data-placement="top" title="upload gambar"  data-id="' . $data['id'] . '" data-images="' . htmlspecialchars(json_encode($images)) . '"><i class="fa fa-upload"></i> Upload Gambar</button>';
                if ($data['is_verification'] == 1) {
                    $buttons .= '
                    <a class="bg-warning mb-2 dropdown-item" data-toggle="tooltip" data-placement="top" title="Edit Invoice" href="' . route('shipping.shipping-detail', $data->id) . '"><i class="fa fa-edit"></i>Edit Invoice</a>
                    <a href="#" class="bg-success mb-2 btnApprove dropdown-item" data-toggle="tooltip" data-placement="top" title="Verifikasi Pembayaran" data-id="' . $data['id'] . '" data-remaining="' . $data['remaining_payment'] . '"><i class="fa fa-money-bill"></i> Verifikasi Pembayaran</a>
                    <a href="#" class="bg-danger mb-2 btnCancel dropdown-item" data-toggle="tooltip" data-placement="top" title="Batalkan Invoice" data-id="' . $data['id'] . '"><i class="fa fa-window-close"></i> Batalkan Invoice</a>
                    ';
                }
                if (Auth::user()->role == 'admin') {
                    $buttons .= '<a href="#" class="bg-danger mb-2 btnDelPayment dropdown-item" data-toggle="tooltip" data-placement="top" title="Delete Payment" data-id="' . $data['id'] . '"><i class="fa fa-window-close"></i> Delete Payment</a>';
                }
                if ($data['ready_packing'] == 0) {
                    $buttons .= '<a href="#" class="bg-success mb-2 btnProses dropdown-item" data-toggle="tooltip" data-placement="top" title="Proses Tracking" data-id="' . $data['id'] . '"><i class="fa fa-check"></i> Proses Tracking</a>';
                }
                $buttons .= '</div>
                            </div>';

                return $buttons;
            })
            ->rawColumns(['action'])
            ->with('ppn_sum', function () use ($query, $request) {
                if ($request->get('dateStart')) {
                    $query = $query->where('invoice_date', '>=', $request->get('dateStart'));
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('invoice_date', '<=', $request->get('dateEnd'));
                }
                if ($request->get('sales_name')) {
                    $query = $query->where('sales_name', $request->get('sales_name'));
                }

                if (!empty($request->get('filterBy'))) {
                    if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                        $query = $query->where('payment_type', $request->get('filterBy'));
                    }

                    if ($request->get('filterBy') == 3) {
                        $query = $query->where('is_verification', 1);
                    }

                    if ($request->get('filterBy') == 4) {
                        $dueDate = Carbon::today()->subDays(3)->toDateString();
                        $query = $query->where('payment_status', 1)->where('payment_due_date', '>', $dueDate);
                    }

                    if ($request->get('filterBy') == 5) {
                        $query = $query->where('invoice_type', 1);
                    }

                    if ($request->get('filterBy') == 6) {
                        $query = $query->where('invoice_type', 3);
                    }

                    if ($request->get('filterBy') == 7) {
                        $query = $query->where('invoice_type', 2);
                    }
                }

                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('invoice_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('shipping_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('destination', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('receipt_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('sales_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('invoice_date', 'like', '%' . $request->search['value'] . '%');
                    });
                }

                return $query->sum('ppn');
            })
            ->with('total_sum', function () use ($query, $request) {
                if ($request->get('dateStart')) {
                    $query = $query->where('invoice_date', '>=', $request->get('dateStart'));
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('invoice_date', '<=', $request->get('dateEnd'));
                }
                if ($request->get('sales_name')) {
                    $query = $query->where('sales_name', $request->get('sales_name'));
                }

                if (!empty($request->get('filterBy'))) {
                    if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                        $query = $query->where('payment_type', $request->get('filterBy'));
                    }

                    if ($request->get('filterBy') == 3) {
                        $query = $query->where('is_verification', 1);
                    }

                    if ($request->get('filterBy') == 4) {
                        $dueDate = Carbon::today()->subDays(3)->toDateString();
                        $query = $query->where('payment_status', 1)->where('payment_due_date', '>', $dueDate);
                    }

                    if ($request->get('filterBy') == 5) {
                        $query = $query->where('invoice_type', 1);
                    }

                    if ($request->get('filterBy') == 6) {
                        $query = $query->where('invoice_type', 3);
                    }

                    if ($request->get('filterBy') == 7) {
                        $query = $query->where('invoice_type', 2);
                    }
                }

                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('invoice_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('shipping_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('destination', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('receipt_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('sales_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('invoice_date', 'like', '%' . $request->search['value'] . '%');
                    });
                }

                return $query->sum('sub_total');
            })
            ->with('dp_sum', function () use ($query, $request) {
                if ($request->get('dateStart')) {
                    $query = $query->where('invoice_date', '>=', $request->get('dateStart'));
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('invoice_date', '<=', $request->get('dateEnd'));
                }
                if ($request->get('sales_name')) {
                    $query = $query->where('sales_name', $request->get('sales_name'));
                }

                if (!empty($request->get('filterBy'))) {
                    if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                        $query = $query->where('payment_type', $request->get('filterBy'));
                    }

                    if ($request->get('filterBy') == 3) {
                        $query = $query->where('is_verification', 1);
                    }

                    if ($request->get('filterBy') == 4) {
                        $dueDate = Carbon::today()->subDays(3)->toDateString();
                        $query = $query->where('payment_status', 1)->where('payment_due_date', '>', $dueDate);
                    }

                    if ($request->get('filterBy') == 5) {
                        $query = $query->where('invoice_type', 1);
                    }

                    if ($request->get('filterBy') == 6) {
                        $query = $query->where('invoice_type', 3);
                    }

                    if ($request->get('filterBy') == 7) {
                        $query = $query->where('invoice_type', 2);
                    }
                }

                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('invoice_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('shipping_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('destination', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('receipt_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('sales_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('invoice_date', 'like', '%' . $request->search['value'] . '%');
                    });
                }

                return $query->sum('down_payment');
            })
            ->with('rm_sum', function () use ($query, $request) {
                if ($request->get('dateStart')) {
                    $query = $query->where('invoice_date', '>=', $request->get('dateStart'));
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('invoice_date', '<=', $request->get('dateEnd'));
                }
                if ($request->get('sales_name')) {
                    $query = $query->where('sales_name', $request->get('sales_name'));
                }

                if (!empty($request->get('filterBy'))) {
                    if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                        $query = $query->where('payment_type', $request->get('filterBy'));
                    }

                    if ($request->get('filterBy') == 3) {
                        $query = $query->where('is_verification', 1);
                    }

                    if ($request->get('filterBy') == 4) {
                        $dueDate = Carbon::today()->subDays(3)->toDateString();
                        $query = $query->where('payment_status', 1)->where('payment_due_date', '>', $dueDate);
                    }

                    if ($request->get('filterBy') == 5) {
                        $query = $query->where('invoice_type', 1);
                    }

                    if ($request->get('filterBy') == 6) {
                        $query = $query->where('invoice_type', 3);
                    }

                    if ($request->get('filterBy') == 7) {
                        $query = $query->where('invoice_type', 2);
                    }
                }

                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('invoice_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('shipping_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('destination', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('receipt_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('sales_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('invoice_date', 'like', '%' . $request->search['value'] . '%');
                    });
                }

                return $query->sum('remaining_payment');
            })
            ->make(true);
    }

    public function list_due_date(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $query = Shipping::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'invoice_number',
            'invoice_date',
            'shipping_name',
            'phone_number',
            'invoice_type',
            'receipt_number',
            'ppn',
            'pph',
            'sub_total',
            'total',
            'down_payment',
            'payment_name',
            'payment_type',
            'payment_status',
            'payment_due_date',
            'destination',
            'is_verification',
            'sales_name'
        ])
            ->where('payment_status', '1')
            ->whereNull('deleted_at')
            ->where(DB::raw("IF(DATEDIFF(payment_due_date, CURRENT_DATE()) <= 3, 1, 0)"), 1);

        $datalist = $query->orderBy('id', 'DESC')->get();
        return DataTables::of($datalist)
            ->filter(function ($instance) use ($request) {
                if ($request->ajax()) {
                    if (!empty($request->get('dateStart'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return ($row['invoice_date'] >= $request->get('dateStart')) ? true : false;
                        });
                    }

                    if (!empty($request->get('dateEnd'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return ($row['invoice_date'] <= $request->get('dateEnd')) ? true : false;
                        });
                    }

                    if (!empty($request->get('filterBy'))) {
                        if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['payment_type'] == $request->get('filterBy')) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 3) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['is_verification'] == 1) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 4) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                $gap = (new GeneralHelpers())->calculate_day_between_two_date($row['payment_due_date'], date('Y-m-d'));
                                return ($row['payment_status'] == 1 && $gap <= 3) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 5) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['invoice_type'] == 1) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 6) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['invoice_type'] == 3) ? true : false;
                            });
                        }

                        if ($request->get('filterBy') == 7) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return ($row['invoice_type'] == 2) ? true : false;
                            });
                        }
                    }

                    if (!empty($request->get('shipperPhoneNumber'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains($row['phone_number'], $request->get('shipperPhoneNumber'))) {
                                return true;
                            }

                            return false;
                        });
                    }

                    if (!empty($request->search['value'])) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['invoice_number']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['shipping_name']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['destination']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['receipt_number']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['invoice_date']), Str::lower($request->search['value']))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['sales_name']), Str::lower($request->search['value']))) {
                                return true;
                            }

                            return false;
                        });
                    }
                }
            })
            ->addColumn('date_diff', function (Shipping $data) {
                $result = (new GeneralHelpers())->calculate_day_between_two_date($data['payment_due_date'], date('Y-m-d'));

                return $result;
            })
            ->rawColumns(['date_diff'])
            ->with('sub_total_sum', function () use ($query, $request) {
                if ($request->get('dateStart')) {
                    $query = $query->where('invoice_date', '>=', $request->get('dateStart'));
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('invoice_date', '<=', $request->get('dateEnd'));
                }

                if (!empty($request->get('filterBy'))) {
                    if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                        $query = $query->where('payment_type', $request->get('filterBy'));
                    }

                    if ($request->get('filterBy') == 3) {
                        $query = $query->where('is_verification', 1);
                    }

                    if ($request->get('filterBy') == 4) {
                        $dueDate = Carbon::today()->subDays(3)->toDateString();
                        $query = $query->where('payment_status', 1)->where('payment_due_date', '>', $dueDate);
                    }

                    if ($request->get('filterBy') == 5) {
                        $query = $query->where('invoice_type', 1);
                    }

                    if ($request->get('filterBy') == 6) {
                        $query = $query->where('invoice_type', 3);
                    }

                    if ($request->get('filterBy') == 7) {
                        $query = $query->where('invoice_type', 2);
                    }
                }

                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('invoice_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('shipping_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('destination', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('receipt_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('sales_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('invoice_date', 'like', '%' . $request->search['value'] . '%');
                    });
                }

                return $query->sum('sub_total');
            })
            ->with('ppn_sum', function () use ($query, $request) {
                if ($request->get('dateStart')) {
                    $query = $query->where('invoice_date', '>=', $request->get('dateStart'));
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('invoice_date', '<=', $request->get('dateEnd'));
                }

                if (!empty($request->get('filterBy'))) {
                    if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                        $query = $query->where('payment_type', $request->get('filterBy'));
                    }

                    if ($request->get('filterBy') == 3) {
                        $query = $query->where('is_verification', 1);
                    }

                    if ($request->get('filterBy') == 4) {
                        $dueDate = Carbon::today()->subDays(3)->toDateString();
                        $query = $query->where('payment_status', 1)->where('payment_due_date', '>', $dueDate);
                    }

                    if ($request->get('filterBy') == 5) {
                        $query = $query->where('invoice_type', 1);
                    }

                    if ($request->get('filterBy') == 6) {
                        $query = $query->where('invoice_type', 3);
                    }

                    if ($request->get('filterBy') == 7) {
                        $query = $query->where('invoice_type', 2);
                    }
                }

                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('invoice_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('shipping_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('destination', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('receipt_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('sales_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('invoice_date', 'like', '%' . $request->search['value'] . '%');
                    });
                }
                return $query->sum('ppn');
            })
            ->with('pph_sum', function () use ($query, $request) {
                if ($request->get('dateStart')) {
                    $query = $query->where('invoice_date', '>=', $request->get('dateStart'));
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('invoice_date', '<=', $request->get('dateEnd'));
                }

                if (!empty($request->get('filterBy'))) {
                    if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                        $query = $query->where('payment_type', $request->get('filterBy'));
                    }

                    if ($request->get('filterBy') == 3) {
                        $query = $query->where('is_verification', 1);
                    }

                    if ($request->get('filterBy') == 4) {
                        $dueDate = Carbon::today()->subDays(3)->toDateString();
                        $query = $query->where('payment_status', 1)->where('payment_due_date', '>', $dueDate);
                    }

                    if ($request->get('filterBy') == 5) {
                        $query = $query->where('invoice_type', 1);
                    }

                    if ($request->get('filterBy') == 6) {
                        $query = $query->where('invoice_type', 3);
                    }

                    if ($request->get('filterBy') == 7) {
                        $query = $query->where('invoice_type', 2);
                    }
                }

                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('invoice_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('shipping_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('destination', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('receipt_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('sales_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('invoice_date', 'like', '%' . $request->search['value'] . '%');
                    });
                }
                return $query->sum('pph');
            })
            ->with('total_sum', function () use ($query, $request) {
                if ($request->get('dateStart')) {
                    $query = $query->where('invoice_date', '>=', $request->get('dateStart'));
                }
                if ($request->get('dateEnd')) {
                    $query = $query->where('invoice_date', '<=', $request->get('dateEnd'));
                }

                if (!empty($request->get('filterBy'))) {
                    if ($request->get('filterBy') == 1 || $request->get('filterBy') == 2) {
                        $query = $query->where('payment_type', $request->get('filterBy'));
                    }

                    if ($request->get('filterBy') == 3) {
                        $query = $query->where('is_verification', 1);
                    }

                    if ($request->get('filterBy') == 4) {
                        $dueDate = Carbon::today()->subDays(3)->toDateString();
                        $query = $query->where('payment_status', 1)->where('payment_due_date', '>', $dueDate);
                    }

                    if ($request->get('filterBy') == 5) {
                        $query = $query->where('invoice_type', 1);
                    }

                    if ($request->get('filterBy') == 6) {
                        $query = $query->where('invoice_type', 3);
                    }

                    if ($request->get('filterBy') == 7) {
                        $query = $query->where('invoice_type', 2);
                    }
                }

                if ($request->search['value']) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('invoice_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('shipping_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('destination', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('receipt_number', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('sales_name', 'like', '%' . $request->search['value'] . '%')
                            ->orWhere('invoice_date', 'like', '%' . $request->search['value'] . '%');
                    });
                }
                return $query->sum('total');
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'invoice_type' => ['required'],
            'invoice_date' => ['required'],
            'shipping_name' => ['required'],
            'shipping_address' => ['required'],
            'shipping_city' => ['required'],
            'phone_number' => ['nullable'],
            'sales_name' => ['required'],
            // 'manifest_id' => ['required'],
            //            'receipt_number' => ['required'],
            //            'destination' => ['required'],
            //            'moda' => ['required'],
            'total' => ['required'],
            'sub_total' => ['required'],
            'ppn' => ['required'],
            'pph' => ['required'],
            'discount' => ['nullable'],
            'down_payment' => ['nullable'],
            'payment_type' => ['required'],
            'payment_due_date' => ['nullable'],
            'include_pph' => ['nullable'],
            'details' => ['required'],
            'banks' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {
            //            $ppn = (new GeneralHelpers())->input_currency_converter(explode(',', $input['ppn']));
            //            $sub_total = (new GeneralHelpers())->input_currency_converter(explode(',', $input['total']));
            //            $total = $ppn+$sub_total;

            //save customer

            $customer = Customer::where('name', strtoupper($input['shipping_name']))->first();
            if ($customer) {
                $customer->update([
                    'phone' => ($input['phone_number']),
                ]);
            } else {
                $customer = Customer::create([
                    'name' => strtoupper($input['shipping_name']),
                    'phone' => ($input['phone_number']),
                ]);
            }

            $shipping = Shipping::create([
                // 'manifest_id' => $input['manifest_id'],
                'invoice_type' => $input['invoice_type'],
                'type' => (int) $input['invoice_type'],
                //                'moda' => $input['moda'],
                'sales_name' => strtoupper($input['sales_name']),
                'invoice_number' => (new GeneralHelpers())->invoice_number(strtoupper($input['shipping_name']), $input['payment_type'], $input['invoice_type']),
                'invoice_date' => $input['invoice_date'],
                'invoice_year' => date('Y'),
                'shipping_name' => strtoupper($input['shipping_name']),
                'shipping_address' => strtoupper($input['shipping_address']),
                'shipping_city' => strtoupper($input['shipping_city']),
                'phone_number' => ($input['phone_number']),
                //                'receipt_number' => $input['receipt_number'],
                //                'destination' => strtoupper($input['destination']),
                'ppn' => $input['ppn'],
                'pph' => $input['pph'],
                'sub_total' => $input['sub_total'],
                'down_payment' => $input['down_payment'],
                'discount' => $input['discount'],
                'total' => $input['total'],
                'remaining_payment' => $input['total'],
                'payment_type' => $input['payment_type'],
                'payment_name' => (new GeneralHelpers())->payment_type($input['payment_type']),
                'payment_due_date' => ($input['payment_type'] != 1) ? $input['payment_due_date'] : date('Y-m-d'),
                'payment_status' => 1,
                'debt_age' => ($input['payment_type'] != 1) ? (new GeneralHelpers())->calculate_day_between_two_date($input['invoice_date'], $input['payment_due_date']) : null,
                'is_verification' => 1,
                'include_pph' => $input['include_pph'],
                'created_by' => Auth()->user()->uuid
            ]);

            for ($i = 0; $i < count($input['details']); $i++) {
                ShippingDetail::create([
                    'shipping_id' => $shipping->id,
                    'manifest_id' => $input['details'][$i]['manifest_id'],
                    'receipt_number' => $input['details'][$i]['receipt_number'],
                    'moda' => $input['details'][$i]['moda'],
                    'destination' => strtoupper($input['details'][$i]['destination']),
                    'starting_price' => $input['details'][$i]['starting_price'],
                    'price' => $input['details'][$i]['price'],
                    'colly' => $input['details'][$i]['colly'] ?? null,
                    'chargeable_weight' => $input['details'][$i]['chargeable_weight'] ?? null,
                    'unit' => $input['details'][$i]['unit'] ?? null,
                    'product' => $input['details'][$i]['product'] ?? null,
                    'price_addons_packing' => $input['details'][$i]['repacking'] ?? null,
                    'price_addons_pickup' => $input['details'][$i]['pickup'] ?? null,
                    'price_addons_dooring' => $input['details'][$i]['dooring'] ?? null,
                    'price_addons_insurance' => $input['details'][$i]['insurance'] ?? null,
                    'minimum_hdl' => $input['details'][$i]['minimum_hdl'] ?? null,
                    'shipdex' => $input['details'][$i]['shipdex'] ?? null,
                    'dus_un' => $input['details'][$i]['dus_un'] ?? null,
                    'acc_xray' => $input['details'][$i]['acc_xray'] ?? null,
                    'adm_smu' => $input['details'][$i]['adm_smu'] ?? null,
                    'forklift' => $input['details'][$i]['forklift'] ?? null,
                    'lalamove_grab' => $input['details'][$i]['lalamove_grab'] ?? null,
                    'remarks' => $input['details'][$i]['remarks'] ?? null,
                    'amount' => $input['details'][$i]['amount'],
                ]);

                $dest[] = strtoupper($input['details'][$i]['destination']);
                $moda[] = strtoupper($input['details'][$i]['moda']);
                $receipt_numbers[] = strtoupper($input['details'][$i]['receipt_number']);
            }



            if (count($dest) > 1) {
                $destination = implode(', ', $dest);
            } else {
                $destination = implode('', $dest);
            }

            if (count($moda) > 1) {
                $moda = implode(', ', $moda);
            } else {
                $moda = implode('', $moda);
            }

            if (count($receipt_numbers) > 1) {
                $receipt_number = implode(', ', $receipt_numbers);
            } else {
                $receipt_number = implode('', $receipt_numbers);
            }

            $shipping->moda = $moda;
            $shipping->receipt_number = $receipt_number;
            $shipping->destination = $destination;
            $shipping->save();
            if (count($input['banks']) > 0) {
                for ($i = 0; $i < count($input['banks']); $i++) {
                    ShippingBank::create([
                        'shipping_id' => $shipping->id,
                        'name_bank' => $input['banks'][$i]['name_bank'],
                        'number_account' => $input['banks'][$i]['number_account'],
                        'name_account' => $input['banks'][$i]['name_account'],
                    ]);
                }
            }

            //            if (($input['payment_type'] == 1)) {
            //                $asset = Asset::where('asset_date', date('Y-m-d'))->first();
            //                if ($asset) {
            //                    $turnover = $asset->turnover + $total;
            //                    $asset->turnover = $turnover;
            //                    $asset->salary_account = (($turnover * 20) / 100);
            //                    $asset->saving_account = (($turnover * 20) / 100);
            //                    $asset->operational = (($turnover * 30) / 100);
            //                    $asset->vendor = (($turnover * 30) / 100);
            //                    $asset->religious_meal = (($turnover * 3.5) / 100);
            //                    $asset->save();
            //                } else {
            //                    $asset = Asset::create([
            //                        'asset_date' => date('Y-m-d'),
            //                        'turnover' => $total,
            //                        'salary_account' => (($total * 20) / 100),
            //                        'saving_account' => (($total * 20) / 100),
            //                        'operational' => (($total * 30) / 100),
            //                        'vendor' => (($total * 30) / 100),
            //                        'religious_meal' => (($total * 3.5) / 100)
            //                    ]);
            //                }
            //
            //                Asset_detail::create([
            //                    'asset_id' => $asset->id,
            //                    'asset_date' => date('Y-m-d'),
            //                    'transaction_id' => $shipping->id,
            //                    'transaction_type' => 1,
            //                    'invoice_number' => $shipping->invoice_number,
            //                    'amount' => $shipping->total,
            //                    'payment_name' => $shipping->payment_name
            //                ]);
            //
            //                $financial = FinancialRecap::first();
            //                $total_turnover = $financial->turnover + $total;
            //                $financial->global_turnover += $total;
            //                $financial->turnover += $total;
            //                $financial->vendor = (($total_turnover * 30) / 100);
            //                $financial->saving = (($total_turnover * 20) / 100);
            //                $financial->salary = (($total_turnover * 20) / 100);
            //                $financial->operational = (($total_turnover * 30) / 100);
            //                $financial->save();
            //            }

            if ($shipping->phone_number) {
                $message = 'Halo ' . $shipping->shipping_name . '!

    Kami ingin memberitahukan bahwa telah dibuat sebuah invoice untuk pengiriman Anda:

    📦 Destinasi Pengiriman: ' . $shipping->destination . '
    📝 Nomor Invoice: ' . $shipping->invoice_number . '
    👤 Nama: ' . $shipping->shipping_name . '
    💰 Jumlah Pembayaran: Rp ' . number_format($shipping->total, 0, ',', '.') . '

    Mohon untuk memeriksa detail invoice ini dengan cermat. Pembayaran dapat dilakukan sebelum tanggal jatuh tempo pada ' . date('d-M-Y', strtotime($shipping->payment_due_date)) . ' untuk memastikan pengiriman tepat waktu.

    Jika ada pertanyaan mengenai pengiriman atau invoice ini, jangan ragu untuk menghubungi kami. Terima kasih atas kepercayaannya!';


                $sendNotif = new WhatsappController();
                $sendNotif->sendMessage($shipping->phone_number, $message);
            }

            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil menyimpan data pengiriman.'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal menyimpan data pengiriman. ' . $exception->getLine() . ' ' . $exception->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $input = $request->validate([
            'sales_name' => ['required'],
            'shipping_name' => ['required'],
            'shipping_address' => ['required'],
            'shipping_city' => ['required'],
            'phone_number' => ['nullable'],
            //            'receipt_number' => ['required'],
            // 'destination' => ['required'],
            //            'moda' => ['required'],
            'total' => ['required'],
            'sub_total' => ['required'],
            'down_payment' => ['nullable'],
            'ppn' => ['required'],
            'pph' => ['required'],
            'payment_type' => ['required'],
            'payment_due_date' => ['nullable'],
            'include_pph' => ['nullable'],
            'details' => ['required'],
            'banks' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {
            $shipping = Shipping::find($request->id);
            $details = ShippingDetail::where('shipping_id', $request->id)->get();

            $invoice_number = explode('/', $shipping->invoice_number);
            $new_invoice_number = $invoice_number[0] . '/' . strtoupper(str_replace(' ', '-', $input['shipping_name'])) . '/' . $invoice_number[2] . '/' . $invoice_number[3] . '/' . $invoice_number[4];
            Shipping::where('id', $request->id)->update([
                // 'manifest_id' => $input['manifest_id'],
                'invoice_number' => $new_invoice_number,
                //                'moda' => $input['moda'],
                'sales_name' => strtoupper($input['sales_name']),
                'shipping_name' => strtoupper($input['shipping_name']),
                'shipping_address' => ($input['shipping_address']),
                'shipping_city' => strtoupper($input['shipping_city']),
                'phone_number' => ($input['phone_number']),
                //                'receipt_number' => $input['receipt_number'],
                //                'destination' => strtoupper($input['destination']),
                'ppn' => $input['ppn'],
                'pph' => $input['pph'],
                'sub_total' => $input['sub_total'],
                'down_payment' => $input['down_payment'],
                'total' => $input['total'],
                'remaining_payment' => $input['total'],
                'payment_type' => $input['payment_type'],
                'payment_name' => (new GeneralHelpers())->payment_type($input['payment_type']),
                'payment_due_date' => ($input['payment_type'] != 1) ? $input['payment_due_date'] : date('Y-m-d'),
                'payment_status' => 1,
                'include_pph' => $input['include_pph'],
                'debt_age' => ($input['payment_type'] != 1) ? (new GeneralHelpers())->calculate_day_between_two_date($shipping->invoice_date, $input['payment_due_date']) : null,
                'is_verification' => 1,
                'created_by' => Auth()->user()->uuid
            ]);

            $shipping_update = Shipping::find($request->id);

            for ($i = 0; $i < count($input['details']); $i++) {
                if (isset($details[$i]->id)) {
                    if ($details[$i]->id == $input['details'][$i]['detail_id']) {
                        ShippingDetail::where('id', $input['details'][$i]['detail_id'])->update([
                            'starting_price' => $input['details'][$i]['starting_price'],
                            'manifest_id' => $input['details'][$i]['manifest_id'],
                            'price' => $input['details'][$i]['price'],
                            'receipt_number' => $input['details'][$i]['receipt_number'],
                            'moda' => $input['details'][$i]['moda'],
                            'destination' => strtoupper($input['details'][$i]['destination']),
                            'colly' => $input['details'][$i]['colly'] ?? null,
                            'chargeable_weight' => $input['details'][$i]['chargeable_weight'] ?? null,
                            'unit' => $input['details'][$i]['unit'] ?? null,
                            'product' => $input['details'][$i]['product'] ?? null,
                            'price_addons_packing' => $input['details'][$i]['repacking'] ?? null,
                            'price_addons_pickup' => $input['details'][$i]['pickup'] ?? null,
                            'price_addons_dooring' => $input['details'][$i]['dooring'] ?? null,
                            'price_addons_insurance' => $input['details'][$i]['insurance'] ?? null,
                            'minimum_hdl' => $input['details'][$i]['minimum_hdl'] ?? null,
                            'shipdex' => $input['details'][$i]['shipdex'] ?? null,
                            'dus_un' => $input['details'][$i]['dus_un'] ?? null,
                            'acc_xray' => $input['details'][$i]['acc_xray'] ?? null,
                            'adm_smu' => $input['details'][$i]['adm_smu'] ?? null,
                            'forklift' => $input['details'][$i]['forklift'] ?? null,
                            'lalamove_grab' => $input['details'][$i]['lalamove_grab'] ?? null,
                            'remarks' => $input['details'][$i]['remarks'] ?? null,
                            'amount' => $input['details'][$i]['amount'],
                        ]);
                    } else {
                        ShippingDetail::where('id', $details[$i]->id)->delete();
                    }
                } else {
                    ShippingDetail::create([
                        'shipping_id' => $request->id,
                        'manifest_id' => $input['details'][$i]['manifest_id'],
                        'starting_price' => $input['details'][$i]['starting_price'],
                        'price' => $input['details'][$i]['price'],
                        'receipt_number' => $input['details'][$i]['receipt_number'],
                        'moda' => $input['details'][$i]['moda'],
                        'destination' => strtoupper($input['details'][$i]['destination']),
                        'colly' => $input['details'][$i]['colly'] ?? null,
                        'chargeable_weight' => $input['details'][$i]['chargeable_weight'] ?? null,
                        'unit' => $input['details'][$i]['unit'] ?? null,
                        'product' => $input['details'][$i]['product'] ?? null,
                        'price_addons_packing' => $input['details'][$i]['repacking'] ?? null,
                        'price_addons_pickup' => $input['details'][$i]['pickup'] ?? null,
                        'price_addons_dooring' => $input['details'][$i]['dooring'] ?? null,
                        'price_addons_insurance' => $input['details'][$i]['insurance'] ?? null,
                        'minimum_hdl' => $input['details'][$i]['minimum_hdl'] ?? null,
                        'shipdex' => $input['details'][$i]['shipdex'] ?? null,
                        'dus_un' => $input['details'][$i]['dus_un'] ?? null,
                        'acc_xray' => $input['details'][$i]['acc_xray'] ?? null,
                        'adm_smu' => $input['details'][$i]['adm_smu'] ?? null,
                        'remarks' => $input['details'][$i]['remarks'] ?? null,
                        'amount' => $input['details'][$i]['amount'],
                    ]);
                }

                $moda[] = ($input['details'][$i]['moda']);
                $dest[] = strtoupper($input['details'][$i]['destination']);
                $receipt_numbers[] = strtoupper($input['details'][$i]['receipt_number']);
            }


            if (count($dest) > 1) {
                $destination = implode(', ', $dest);
            } else {
                $destination = implode('', $dest);
            }

            if (count($moda) > 1) {
                $moda_ = implode(', ', $moda);
            } else {
                $moda_ = implode('', $moda);
            }

            if (count($receipt_numbers) > 1) {
                $receipt_number = implode(', ', $receipt_numbers);
            } else {
                $receipt_number = implode('', $receipt_numbers);
            }

            $shipping->moda = $moda_;
            $shipping->receipt_number = $receipt_number;
            $shipping->destination = $destination;
            $shipping->save();

            $details_update = ShippingDetail::where('shipping_id', $request->id)->get();

            //banks

            ShippingBank::where('shipping_id', $request->id)->delete();
            if (count($input['banks']) > 0) {
                for ($i = 0; $i < count($input['banks']); $i++) {
                    ShippingBank::create([
                        'shipping_id' => $shipping->id,
                        'name_bank' => $input['banks'][$i]['name_bank'],
                        'number_account' => $input['banks'][$i]['number_account'],
                        'name_account' => $input['banks'][$i]['name_account'],
                    ]);
                }
            }

            Logs::create([
                'log_type' => Shipping::IMAGE_PATH,
                'foreign_key' => $request->id,
                'action' => 'UPDATE',
                'prior_update' => json_encode(['shipping' => $shipping, 'details' => $details]),
                'after_update' => json_encode(['shipping' => $shipping_update, 'details' => $details_update]),
                'update_by' => Auth()->user()->uuid,
                'update_name' => Auth()->user()->full_name
            ]);

            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil mengubah data pengiriman.'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal mengubah data pengiriman. ' . $exception->getLine() . ' ' . $exception->getMessage()
            ]);
        }
    }

    public function approve(Request $request)
    {
        $shipping = Shipping::find($request->shipping_id);

        if (isset($shipping)) {
            DB::beginTransaction();
            try {
                $asset = Asset::where('asset_date', date('Y-m-d'))->first();

                if ($request->payment_type == 'down_payment') {
                    $shipping->down_payment = (int) $request->nominal[1];
                    $shipping->remaining_payment = $shipping->total - (int) $request->nominal[1];
                    $shipping->save();

                    if ($asset) {
                        $asset->turnover += $shipping->down_payment;
                        $asset->save();
                    } else {
                        $asset = Asset::create([
                            'asset_date' => date('Y-m-d'),
                            'turnover' => $shipping->down_payment,
                            'salary_account' => 0,
                            'saving_account' => 0,
                            'operational' => 0,
                            'vendor' => 0,
                            'religious_meal' => 0
                        ]);
                    }

                    Asset_detail::create([
                        'asset_id' => $asset->id,
                        'asset_date' => date('Y-m-d'),
                        'transaction_id' => $shipping->id,
                        'transaction_type' => 1,
                        'invoice_number' => $shipping->invoice_number,
                        'amount' => $shipping->down_payment,
                        'payment_name' => $shipping->payment_name
                    ]);

                    $financial = FinancialRecap::first();
                    $financial->global_turnover += $shipping->down_payment;
                    $financial->save();

                    ShippingPayment::create([
                        'shipping_id' => $request->shipping_id,
                        'verification_type' => 'Down_payment',
                        'total_pay' => 1,
                        'nominal' => $request->nominal[1],
                        'bank_name' => $request->bank,
                        'created_by' => Auth()->user()->uuid,
                        'created_name' => Auth()->user()->full_name
                    ]);

                    if ($shipping->phone_number) {


                        $message = 'Hi ' . $shipping->shipping_name . ', Terima kasih. Pembayaran ' . $shipping->invoice_number . ' Jumlah Pembayaran: ' . number_format($shipping->down_payment, 0, ',', '.') .
                            ' sudah kami terima. Saldo piutang akhir: ' . number_format($shipping->remaining_payment, 0, ',', '.') . '

-MAS CARGO EXPRESS-

_Pesan ini dikirim otomatis oleh sistem._';

                        $sendNotif = new WhatsappController();
                        $sendNotif->sendMessage($shipping->phone_number, $message);
                    }
                } else {
                    if (!empty($request->vendor_percentage) || !empty($request->operational_percentage) || !empty($request->salary_percentage) || !empty($request->saving_percentage)) {

                        $gap_vendor = 100 - (int) $request->vendor_percentage;
                        $gap_operational = 100 - (int) $request->operational_percentage;
                        $gap_salary = 100 - (int) $request->salary_percentage;
                        $gap_saving = 100 - (int) $request->saving_percentage;

                        $vendor_percentage = (int) $request->vendor_percentage;
                        $operational_percentage = (int) $request->operational_percentage;
                        $salary_percentage = (int) $request->salary_percentage;
                        $saving_percentage = (int) $request->saving_percentage;

                        if ($request->total_pos == '1') {
                            //1 pos 100%
                            if ($gap_vendor == 0 && $request->operational_percentage == null && $request->salary_percentage == null && $request->saving_percentage == null) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * (int) $request->vendor_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => 0,
                                        'saving_account' => 0,
                                        'operational' => 0,
                                        'vendor' => (($shipping->remaining_payment * (int) $request->vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * (int) $request->vendor_percentage) / 100);
                                $financial->save();
                            }

                            if ($gap_operational == 0 && $request->vendor_percentage == null && $request->salary_percentage == null && $request->saving_percentage == null) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->operational = (($turnover * (int) $request->operational_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => 0,
                                        'saving_account' => 0,
                                        'operational' => (($shipping->remaining_payment * (int) $request->operational_percentage) / 100),
                                        'vendor' => 0,
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->operational = (($total_turnover * (int) $request->operational_percentage) / 100);
                                $financial->save();
                            }

                            if ($gap_salary == 0 && $request->vendor_percentage == null && $request->operational_percentage == null && $request->saving_percentage == null) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->salary_account = (($turnover * (int) $request->salary_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * (int) $request->salary_percentage) / 100),
                                        'saving_account' => 0,
                                        'operational' => 0,
                                        'vendor' => 0,
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->salary = (($total_turnover * (int) $request->salary_percentage) / 100);
                                $financial->save();
                            }

                            if ($gap_saving == 0 && $request->vendor_percentage == null && $request->operational_percentage == null && $request->salary_percentage == null) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->saving_account = (($turnover * (int) $request->saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => 0,
                                        'saving_account' => (($shipping->remaining_payment * (int) $request->saving_percentage) / 100),
                                        'operational' => 0,
                                        'vendor' => 0,
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->saving = (($total_turnover * (int) $request->saving_percentage) / 100);
                                $financial->save();
                            }

                            //1 pos < 100%
                            if ($gap_vendor > 0 && $request->operational_percentage == null && $request->salary_percentage == null && $request->saving_percentage == null) {
                                $remaining_percentage = $gap_vendor / 3;

                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;

                                    $asset->salary_account = (($turnover * $remaining_percentage) / 100);
                                    $asset->saving_account = (($turnover * $remaining_percentage) / 100);
                                    $asset->operational = (($turnover * $remaining_percentage) / 100);
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->saving = (($total_turnover * $remaining_percentage) / 100);
                                $financial->salary = (($total_turnover * $remaining_percentage) / 100);
                                $financial->operational = (($total_turnover * $remaining_percentage) / 100);
                                $financial->save();
                            }

                            if ($gap_operational > 0 && $request->vendor_percentage == null && $request->salary_percentage == null && $request->saving_percentage == null) {
                                $remaining_percentage = $gap_operational / 3;

                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;

                                    $asset->salary_account = (($turnover * $remaining_percentage) / 100);
                                    $asset->saving_account = (($turnover * $remaining_percentage) / 100);
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->vendor = (($turnover * $remaining_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * $remaining_percentage) / 100);
                                $financial->saving = (($total_turnover * $remaining_percentage) / 100);
                                $financial->salary = (($total_turnover * $remaining_percentage) / 100);
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->save();
                            }

                            if ($gap_salary > 0 && $request->vendor_percentage == null && $request->operational_percentage == null && $request->saving_percentage == null) {
                                $remaining_percentage = $gap_salary / 3;

                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;

                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->saving_account = (($turnover * $remaining_percentage) / 100);
                                    $asset->operational = (($turnover * $remaining_percentage) / 100);
                                    $asset->vendor = (($turnover * $remaining_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * $remaining_percentage) / 100);
                                $financial->saving = (($total_turnover * $remaining_percentage) / 100);
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->operational = (($total_turnover * $remaining_percentage) / 100);
                                $financial->save();
                            }

                            if ($gap_saving > 0 && $request->vendor_percentage == null && $request->operational_percentage == null && $request->salary_percentage == null) {
                                $remaining_percentage = $gap_saving / 3;

                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;

                                    $asset->salary_account = (($turnover * $remaining_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->operational = (($turnover * $remaining_percentage) / 100);
                                    $asset->vendor = (($turnover * $remaining_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * $remaining_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->salary = (($total_turnover * $remaining_percentage) / 100);
                                $financial->operational = (($total_turnover * $remaining_percentage) / 100);
                                $financial->save();
                            }
                        }

                        if ($request->total_pos == '2') {
                            //2 pos == 100
                            if ($vendor_percentage + $operational_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => 0,
                                        'saving_account' => 0,
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->save();
                            }

                            if ($vendor_percentage + $salary_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => 0,
                                        'operational' => 0,
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->save();
                            }

                            if ($vendor_percentage + $saving_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => 0,
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => 0,
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->save();
                            }

                            if ($operational_percentage + $salary_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => 0,
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => 0,
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->save();
                            }

                            if ($operational_percentage + $saving_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => 0,
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => 0,
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->save();
                            }

                            if ($salary_percentage + $saving_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => 0,
                                        'vendor' => 0,
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->save();
                            }

                            //2 pos < 100
                            if ($vendor_percentage != 0 && $operational_percentage != 0) {
                                if ($vendor_percentage + $operational_percentage < 100) {
                                    $remaining_percentage = (100 - ($vendor_percentage + $operational_percentage)) / 2;

                                    if ($asset) {
                                        $turnover = $asset->turnover + $shipping->remaining_payment;
                                        $asset->turnover = $turnover;
                                        $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                        $asset->operational = (($turnover * $operational_percentage) / 100);
                                        $asset->salary_account = (($turnover * $remaining_percentage) / 100);
                                        $asset->saving_account = (($turnover * $remaining_percentage) / 100);
                                        $asset->save();
                                    } else {
                                        $asset = Asset::create([
                                            'asset_date' => date('Y-m-d'),
                                            'turnover' => $shipping->remaining_payment,
                                            'salary_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'saving_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                            'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                            'religious_meal' => 0
                                        ]);
                                    }

                                    $financial = FinancialRecap::first();
                                    $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                    $financial->global_turnover += $shipping->remaining_payment;
                                    $financial->turnover += $shipping->remaining_payment;
                                    $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                    $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                    $financial->salary = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->saving = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->save();
                                }
                            }

                            if ($vendor_percentage != 0 && $salary_percentage != 0) {
                                if ($vendor_percentage + $salary_percentage < 100) {
                                    $remaining_percentage = (100 - ($vendor_percentage + $salary_percentage)) / 2;
                                    $turnover = $asset->turnover + $shipping->remaining_payment;

                                    if ($asset) {
                                        $asset->turnover = $turnover;
                                        $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                        $asset->operational = (($turnover * $remaining_percentage) / 100);
                                        $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                        $asset->saving_account = (($turnover * $remaining_percentage) / 100);
                                        $asset->save();
                                    } else {
                                        $asset = Asset::create([
                                            'asset_date' => date('Y-m-d'),
                                            'turnover' => $shipping->remaining_payment,
                                            'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                            'saving_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'operational' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                            'religious_meal' => 0
                                        ]);
                                    }

                                    $financial = FinancialRecap::first();
                                    $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                    $financial->global_turnover += $shipping->remaining_payment;
                                    $financial->turnover += $shipping->remaining_payment;
                                    $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                    $financial->operational = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                    $financial->saving = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->save();
                                }
                            }

                            if ($vendor_percentage != 0 && $saving_percentage != 0) {
                                if ($vendor_percentage + $saving_percentage < 100) {
                                    $remaining_percentage = (100 - ($vendor_percentage + $saving_percentage)) / 2;

                                    if ($asset) {
                                        $turnover = $asset->turnover + $shipping->remaining_payment;
                                        $asset->turnover = $turnover;
                                        $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                        $asset->operational = (($turnover * $remaining_percentage) / 100);
                                        $asset->salary_account = (($turnover * $remaining_percentage) / 100);
                                        $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                        $asset->save();
                                    } else {
                                        $asset = Asset::create([
                                            'asset_date' => date('Y-m-d'),
                                            'turnover' => $shipping->remaining_payment,
                                            'salary_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                            'operational' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                            'religious_meal' => 0
                                        ]);
                                    }

                                    $financial = FinancialRecap::first();
                                    $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                    $financial->global_turnover += $shipping->remaining_payment;
                                    $financial->turnover += $shipping->remaining_payment;
                                    $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                    $financial->operational = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->salary = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                    $financial->save();
                                }
                            }

                            if ($operational_percentage != 0 && $salary_percentage != 0) {
                                if ($operational_percentage + $salary_percentage < 100) {
                                    $remaining_percentage = (100 - ($operational_percentage + $salary_percentage)) / 2;

                                    if ($asset) {
                                        $turnover = $asset->turnover + $shipping->remaining_payment;
                                        $asset->turnover = $turnover;
                                        $asset->vendor = (($turnover * $remaining_percentage) / 100);
                                        $asset->operational = (($turnover * $operational_percentage) / 100);
                                        $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                        $asset->saving_account = (($turnover * $remaining_percentage) / 100);
                                        $asset->save();
                                    } else {
                                        $asset = Asset::create([
                                            'asset_date' => date('Y-m-d'),
                                            'turnover' => $shipping->remaining_payment,
                                            'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                            'saving_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                            'vendor' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'religious_meal' => 0
                                        ]);
                                    }

                                    $financial = FinancialRecap::first();
                                    $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                    $financial->global_turnover += $shipping->remaining_payment;
                                    $financial->turnover += $shipping->remaining_payment;
                                    $financial->vendor = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                    $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                    $financial->saving = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->save();
                                }
                            }

                            if ($operational_percentage != 0 && $saving_percentage != 0) {
                                if ($operational_percentage + $saving_percentage < 100) {
                                    $remaining_percentage = (100 - ($operational_percentage + $saving_percentage)) / 2;

                                    if ($asset) {
                                        $turnover = $asset->turnover + $shipping->remaining_payment;
                                        $asset->turnover = $turnover;
                                        $asset->vendor = (($turnover * $remaining_percentage) / 100);
                                        $asset->operational = (($turnover * $operational_percentage) / 100);
                                        $asset->salary_account = (($turnover * $remaining_percentage) / 100);
                                        $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                        $asset->save();
                                    } else {
                                        $asset = Asset::create([
                                            'asset_date' => date('Y-m-d'),
                                            'turnover' => $shipping->remaining_payment,
                                            'salary_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                            'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                            'vendor' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'religious_meal' => 0
                                        ]);
                                    }

                                    $financial = FinancialRecap::first();
                                    $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                    $financial->global_turnover += $shipping->remaining_payment;
                                    $financial->turnover += $shipping->remaining_payment;
                                    $financial->vendor = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                    $financial->salary = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                    $financial->save();
                                }
                            }

                            if ($salary_percentage != 0 && $saving_percentage != 0) {
                                if ($salary_percentage + $saving_percentage < 100) {
                                    $remaining_percentage = (100 - ($salary_percentage + $saving_percentage)) / 2;

                                    if ($asset) {
                                        $turnover = $asset->turnover + $shipping->remaining_payment;
                                        $asset->turnover = $turnover;
                                        $asset->vendor = (($turnover * $remaining_percentage) / 100);
                                        $asset->operational = (($turnover * $remaining_percentage) / 100);
                                        $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                        $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                        $asset->save();
                                    } else {
                                        $asset = Asset::create([
                                            'asset_date' => date('Y-m-d'),
                                            'turnover' => $shipping->remaining_payment,
                                            'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                            'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                            'operational' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'vendor' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                            'religious_meal' => 0
                                        ]);
                                    }

                                    $financial = FinancialRecap::first();
                                    $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                    $financial->global_turnover += $shipping->remaining_payment;
                                    $financial->turnover += $shipping->remaining_payment;
                                    $financial->vendor = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->operational = (($total_turnover * $remaining_percentage) / 100);
                                    $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                    $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                    $financial->save();
                                }
                            }
                        }

                        if ($request->total_pos == '3') {
                            //3 pos == 100
                            if ($vendor_percentage + $operational_percentage + $salary_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => 0,
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->save();
                            }

                            if ($vendor_percentage + $operational_percentage + $saving_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => 0,
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->save();
                            }

                            if ($operational_percentage + $salary_percentage + $saving_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => 0,
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->save();
                            }

                            if ($vendor_percentage + $salary_percentage + $saving_percentage == 100) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => 0,
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->save();
                            }

                            //3 pos < 100
                            if ($vendor_percentage + $operational_percentage + $salary_percentage < 100) {
                                $remaining_percentage = (100 - ($vendor_percentage + $operational_percentage + $salary_percentage));
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->saving_account = (($turnover * $remaining_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->saving = (($total_turnover * $remaining_percentage) / 100);
                                $financial->save();
                            }

                            if ($vendor_percentage + $operational_percentage + $saving_percentage < 100) {
                                $remaining_percentage = (100 - ($vendor_percentage + $operational_percentage + $saving_percentage));
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->salary_account = (($turnover * $remaining_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->salary = (($total_turnover * $remaining_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->save();
                            }

                            if ($operational_percentage + $salary_percentage + $saving_percentage < 100) {
                                $remaining_percentage = (100 - ($operational_percentage + $salary_percentage + $saving_percentage));
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * $remaining_percentage) / 100);
                                    $asset->operational = (($turnover * $operational_percentage) / 100);
                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $operational_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->operational = (($total_turnover * $operational_percentage) / 100);
                                $financial->vendor = (($total_turnover * $remaining_percentage) / 100);
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->save();
                            }

                            if ($vendor_percentage + $salary_percentage + $saving_percentage < 100) {
                                $remaining_percentage = (100 - ($vendor_percentage + $salary_percentage + $saving_percentage));
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;
                                    $asset->vendor = (($turnover * $vendor_percentage) / 100);
                                    $asset->operational = (($turnover * $remaining_percentage) / 100);
                                    $asset->salary_account = (($turnover * $salary_percentage) / 100);
                                    $asset->saving_account = (($turnover * $saving_percentage) / 100);
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * $salary_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * $saving_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * $remaining_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * $vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->operational = (($total_turnover * $remaining_percentage) / 100);
                                $financial->vendor = (($total_turnover * $vendor_percentage) / 100);
                                $financial->salary = (($total_turnover * $salary_percentage) / 100);
                                $financial->saving = (($total_turnover * $saving_percentage) / 100);
                                $financial->save();
                            }
                        }

                        if ($request->total_pos == '4') {
                            //4 pos
                            if ($vendor_percentage > 0 && $operational_percentage > 0 && $salary_percentage > 0 && $saving_percentage > 0) {
                                if ($asset) {
                                    $turnover = $asset->turnover + $shipping->remaining_payment;
                                    $asset->turnover = $turnover;

                                    $asset->salary_account = (($turnover * (int) $request->salary_percentage) / 100);
                                    $asset->saving_account = (($turnover * (int) $request->saving_percentage) / 100);
                                    $asset->operational = (($turnover * (int) $request->operational_percentage) / 100);
                                    $asset->vendor = (($turnover * (int) $request->vendor_percentage) / 100);
                                    $asset->religious_meal = 0;
                                    $asset->save();
                                } else {
                                    $asset = Asset::create([
                                        'asset_date' => date('Y-m-d'),
                                        'turnover' => $shipping->remaining_payment,
                                        'salary_account' => (($shipping->remaining_payment * (int) $request->salary_percentage) / 100),
                                        'saving_account' => (($shipping->remaining_payment * (int) $request->saving_percentage) / 100),
                                        'operational' => (($shipping->remaining_payment * (int) $request->operational_percentage) / 100),
                                        'vendor' => (($shipping->remaining_payment * (int) $request->vendor_percentage) / 100),
                                        'religious_meal' => 0
                                    ]);
                                }

                                $financial = FinancialRecap::first();
                                $total_turnover = $financial->turnover + $shipping->remaining_payment;
                                $financial->global_turnover += $shipping->remaining_payment;
                                $financial->turnover += $shipping->remaining_payment;
                                $financial->vendor = (($total_turnover * (int) $request->vendor_percentage) / 100);
                                $financial->saving = (($total_turnover * (int) $request->saving_percentage) / 100);
                                $financial->salary = (($total_turnover * (int) $request->salary_percentage) / 100);
                                $financial->operational = (($total_turnover * (int) $request->operational_percentage) / 100);
                                $financial->save();
                            }
                        }

                        Asset_detail::create([
                            'asset_id' => $asset->id,
                            'asset_date' => date('Y-m-d'),
                            'transaction_id' => $shipping->id,
                            'transaction_type' => 1,
                            'invoice_number' => $shipping->invoice_number,
                            'amount' => $shipping->remaining_payment,
                            'payment_name' => $shipping->payment_name
                        ]);
                    } else {
                        if ($asset) {
                            $turnover = $asset->turnover + $shipping->remaining_payment;
                            $asset->turnover = $turnover;
                            $asset->salary_account = (($turnover * 20) / 100);
                            $asset->saving_account = (($turnover * 20) / 100);
                            $asset->operational = (($turnover * 30) / 100);
                            $asset->vendor = (($turnover * 30) / 100);
                            $asset->save();
                        } else {
                            $asset = Asset::create([
                                'asset_date' => date('Y-m-d'),
                                'turnover' => $shipping->remaining_payment,
                                'salary_account' => (($shipping->remaining_payment * 20) / 100),
                                'saving_account' => (($shipping->remaining_payment * 20) / 100),
                                'operational' => (($shipping->remaining_payment * 30) / 100),
                                'vendor' => (($shipping->remaining_payment * 30) / 100),
                            ]);
                        }

                        Asset_detail::create([
                            'asset_id' => $asset->id,
                            'asset_date' => date('Y-m-d'),
                            'transaction_id' => $shipping->id,
                            'transaction_type' => 1,
                            'invoice_number' => $shipping->invoice_number,
                            'amount' => $shipping->remaining_payment,
                            'vendor_payment' => (($shipping->remaining_payment * 30) / 100),
                            'operational_payment' => (($shipping->remaining_payment * 30) / 100),
                            'salary_payment' => (($shipping->remaining_payment * 20) / 100),
                            'saving_payment' => (($shipping->remaining_payment * 20) / 100),
                            'payment_name' => $shipping->payment_name
                        ]);

                        $financial = FinancialRecap::first();
                        $total_turnover = $financial->turnover + $shipping->remaining_payment;
                        $financial->global_turnover += $shipping->remaining_payment;
                        $financial->turnover += $shipping->remaining_payment;
                        $financial->vendor += (($shipping->remaining_payment * 30) / 100);
                        $financial->saving += (($shipping->remaining_payment * 20) / 100);
                        $financial->salary += (($shipping->remaining_payment * 20) / 100);
                        $financial->operational += (($shipping->remaining_payment * 30) / 100);
                        $financial->save();
                    }

                    $shipping->payment_status = 2;
                    $shipping->is_verification = 2;
                    $shipping->verifier_name = Auth()->user()->full_name;
                    $shipping->payment_date = $request->payment_date;
                    $shipping->save();

                    for ($i = 0; $i < count($request->detail_payment); $i++) {
                        ShippingPayment::create([
                            'shipping_id' => $request->shipping_id,
                            'verification_type' => 'Repayment',
                            'total_pay' => $request->total_pay,
                            'nominal' => $request->detail_payment[$i]['nominal'],
                            'bank_name' => $request->detail_payment[$i]['bank'],
                            'created_by' => Auth()->user()->uuid,
                            'created_name' => Auth()->user()->full_name
                        ]);
                    }

                    if ($shipping->phone_number) {


                        $message = 'Hi ' . $shipping->shipping_name . ', Terima kasih. Pembayaran ' . $shipping->invoice_number . ' Jumlah Pembayaran: Rp ' . number_format($shipping->remaining_payment, 0, ',', '.') .
                            ' sudah kami terima.

-MAS CARGO EXPRESS-

_Pesan ini dikirim otomatis oleh sistem._';

                        $sendNotif = new WhatsappController();
                        $sendNotif->sendMessage($shipping->phone_number, $message);
                    }
                }


                DB::commit();
                return response()->json([
                    'status' => 200,
                    'msg' => 'Berhasil melakukan verifikasi pembayaran.'
                ]);
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'msg' => 'Gagal melakukan verifikasi pembayaran. ' . $exception->getLine() . ' ' . $exception->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'status' => 500,
                'msg' => 'Tidak ada pembayaran untuk invoice tersebut.'
            ]);
        }
    }

    public function print_invoice($id)
    {
        // dd($id);
        ini_set("pcre.backtrack_limit", "5000000");
        ini_set("memory_limit", "-1");
        $shipping = Shipping::find($id);
        $banks = ShippingBank::where('shipping_id', $id)->get();
        $details = ShippingDetail::with('manifest.product')->where('shipping_id', $id)->get();
        $images = ShippingImage::where('shipping_id', $id)->get();

        $documentFileName = $shipping->invoice_number . ".pdf";

        // Create the mPDF document
        // dd($details);

        try {
            $document = new PDF([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'Landscape',
                'margin_header' => '3',
                'margin_top' => '5',
                'margin_bottom' => '5',
                'margin_footer' => '2',
            ]);
            $document->showImageErrors = true;
            $document->imageVars['myvariable'] = file_get_contents(public_path('masexpress.png'));
            // $document->curlAllowUnsafeSslRequests = true;
            // Set some header informations for output
            $header = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
            ];

            // Write some simple Content
            // $stylesheet = file_get_contents(\asset('assets')."/dist/mpdf.css");
            $imgRow = count($images);
            $detailCount = count($details);

            $row = ($imgRow + $detailCount);

            if ($shipping->invoice_type == '1') {
                $html = view('shipping.invoice', ['data' => $shipping, 'banks' => $banks, 'details' => $details, 'images' => $images, 'row' => $row, 'imgRow' => $imgRow]);
            } elseif ($shipping->invoice_type == '2') {
                $html = view('shipping.invoice-kendaraan', ['data' => $shipping, 'banks' => $banks, 'details' => $details, 'images' => $images, 'row' => $row, 'imgRow' => $imgRow]);
            } else {
                $html = view('shipping.invoice-udara', ['data' => $shipping, 'banks' => $banks, 'details' => $details, 'images' => $images, 'row' => $row, 'imgRow' => $imgRow]);
            }
            // $document->WriteHTML($stylesheet,1);
            $document->img_dpi = 50;
            // $document->packTableData = true;
            $document->writeHTML($html, \Mpdf\HTMLParserMode::DEFAULT_MODE);

            // Save PDF on your public storage
            $document->Output($documentFileName, "I");
            // Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));


            // return Storage::disk('public')->download($documentFileName, 'Request', $header);
        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            echo $e->getMessage();
        }
    }

    public function export_excel(Request $request)
    {
        return Excel::download(new ShippingExport($request->all()), 'invoice.xlsx');
    }

    public function upload_img_invoice(Request $request)
    {
        $input = $request->validate([
            'shipping_id' => ['required'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3048'],
        ]);

        $image = $request->file('image');
        $image_uploaded_path = $image->store(Shipping::IMAGE_PATH, 'public');

        ShippingImage::create([
            'shipping_id' => $input['shipping_id'],
            'image' => $image_uploaded_path,
            'created_by' => Auth()->user()->uuid,
            'created_name' => Auth()->user()->full_name
        ]);

        // return $this->sendResponse(true, 200, '');
        return redirect()->back()->with('success', 'Berhasil upload image.');
    }

    public function getById(Request $request)
    {
        $payload = Shipping::find($request->shipping_id);

        return $payload;
    }

    public function delete_image(Request $request)
    {
        $input = $request->validate([
            'id' => ['required']
        ]);

        DB::beginTransaction();
        try {
            ShippingImage::where('id', $input['id'])->delete();

            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil melakukan penghapusan gambar.'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal melakukan penghapusan gambar. ' . $exception->getLine() . ' ' . $exception->getMessage()
            ]);
        }
    }

    public function delete_invoice(Request $request)
    {
        $input = $request->validate([
            'id' => ['required']
        ]);

        DB::beginTransaction();
        try {
            Shipping::where('id', $input['id'])->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => Auth()->user()->uuid
            ]);

            $manifest_ids = ShippingDetail::where('shipping_id', $input['id'])->get()->pluck('manifest_id');

            Manifest::whereIn('id', $manifest_ids)->delete();

            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil melakukan pembatalan invoice.'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal melakukan pembatalan invoice. ' . $exception->getLine() . ' ' . $exception->getMessage()
            ]);
        }
    }

    public function send_notification(Request $request)
    {
        $request->validate([
            'id' => ['required'],
        ]);

        $shipping = Shipping::find($request->id);

        if (!$shipping) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Data Tidak Ditemukan'
            ], 500);
        }

        if (!$shipping->phone_number) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Nomor Telepon Belum Diisi'
            ], 404);
        }

        $message = 'Ini adalah notifikasi pembayaran untuk faktur Anda:

    📦 Destinasi Pengiriman: ' . $shipping->destination . '
    📝 Nomor Invoice: ' . $shipping->invoice_number . '
    👤 Nama: ' . $shipping->shipping_name . '
    💰 Sisa Jumlah Pembayaran: Rp ' . number_format($shipping->remaining_payment, 0, ',', '.') . '

Mohon segera lakukan pembayaran sebelum tanggal jatuh tempo pada ' . date('d-M-Y', strtotime($shipping->payment_due_date)) . '.

Terima kasih atas kerjasamanya! Jika ada pertanyaan, jangan ragu untuk menghubungi kami.';

        $sendNotif = new WhatsappController();
        $sendNotif->sendMessage($shipping->phone_number, $message);

        return $shipping;
    }


    public function deletePayment(Request $request)
    {
        $request->validate([
            'shipping_id' => ['required'],
        ]);

        DB::beginTransaction();
        try {


            $shipping = Shipping::find($request->shipping_id);
            $payments = ShippingPayment::where('shipping_id', $shipping->id)->get();

            if ($payments->count() == 0) {
                return response()->json([
                    'status' => 500,
                    'msg' => 'Tidak ada data pembayaran'
                ]);
            }
            //set to unverified
            $shipping->payment_status = 1;
            $shipping->is_verification = 1;
            $shipping->save();


            foreach ($payments as $payment) {
                //get asset detail

                $detail_asset = Asset_detail::where('invoice_number', $shipping->invoice_number)->get();

                foreach ($detail_asset as $detail) {
                    //kurangi omset
                    $asset = Asset::find($detail->asset_id);
                    $asset->turnover -= $detail->amount;
                    $asset->salary_account -= $detail->salary_payment;
                    $asset->saving_account -= $detail->saving_payment;
                    $asset->operational -= $detail->operational_payment;
                    $asset->vendor -= $detail->vendor_payment;
                    $asset->save();

                    $financial_recap = FinancialRecap::first();
                    $financial_recap->operational -= $detail->operational_payment;
                    $financial_recap->turnover -= $detail->amount;
                    $financial_recap->global_turnover -= $detail->amount;
                    $financial_recap->salary -= $detail->salary_payment;
                    $financial_recap->saving -= $detail->saving_payment;
                    $financial_recap->vendor -= $detail->vendor_payment;
                    $financial_recap->save();


                    $detail->delete();
                }

                $payment->delete();
            }



            DB::commit();
            return response()->json([
                'status' => 200,
                'msg' => 'Berhasil hapus data payment'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'msg' => 'Gagal menyimpan mengapus data pembayaran. ' . $exception->getLine() . ' ' . $exception->getMessage()
            ]);
        }
    }

    public function updateReadyPacking(Request $request, $id)
    {
        $request->validate([
            'ready_packing' => ['required', 'in:0,1'],
        ]);
        Shipping::where('id', $id)->update([
            'ready_packing' => $request->ready_packing
        ]);


        return response()->json([
            'status' => 200,
            'msg' => 'Berhasil Update Status Packing'
        ]);
    }

    
}
