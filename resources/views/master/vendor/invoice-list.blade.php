@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Invoice Vendor {{ $vendor->name }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('master.vendors.index') }}">Vendors</a></li>
                            <li class="breadcrumb-item active">Invoice Vendor</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-lime">
                            <div class="inner">
                                <h3>{{ isset($totalInvoice) ? (new \App\Helpers\GeneralHelpers())->currency($totalInvoice) : 0 }}
                                </h3>

                                <p>Invoice Total</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-invoice"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-lime">
                            <div class="inner">
                                <h3>{{ isset($totalInvoiceMonth) ? (new \App\Helpers\GeneralHelpers())->currency($totalInvoiceMonth) : 0 }}
                                </h3>

                                <p>Invoice
                                    {{ strtoupper((new \App\Helpers\GeneralHelpers())->convert_date_name_month(date('Y-m-d'))) . ' ' . date('Y') }}
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-invoice"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ (new \App\Helpers\GeneralHelpers())->currency($payment) }}</h3>

                                <p>Total Payment
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-receipt"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ (new \App\Helpers\GeneralHelpers())->currency($paymentMonth) }}</h3>

                                <p>Total Payment
                                    {{ strtoupper((new \App\Helpers\GeneralHelpers())->convert_date_name_month(date('Y-m-d'))) . ' ' . date('Y') }}
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-receipt"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ (new \App\Helpers\GeneralHelpers())->currency($saldoTotal) }}</h3>

                                <p>Saldo Total</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money-bill"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    {{-- <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ (new \App\Helpers\GeneralHelpers())->currency($saldoMonth) }}</h3>

                                <p>Saldo
                                    {{ strtoupper((new \App\Helpers\GeneralHelpers())->convert_date_name_month(date('Y-m-d'))) . ' ' . date('Y') }}
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money-bill"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div> --}}
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-lime">
                            <div class="inner">
                                <h3>{{ $qtyInvoiceMont ?? 0 }}</h3>

                                <p>Banyak Invoice
                                    {{ strtoupper((new \App\Helpers\GeneralHelpers())->convert_date_name_month(date('Y-m-d'))) . ' ' . date('Y') }}
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-invoice"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $due_date ?? 0 }}</h3>

                                <p>Invoice Jatuh Tempo</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-times"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            {{-- <div class="card-header">
                                <h3 class="card-title">
                                    <button class="btn btn-block btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-add-vendor"><i class="fa fa-plus"></i>Tambah Invoice</button>
                                </h3>
                            </div> --}}
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th>No</th> --}}
                                            <th>Nama Vendor</th>
                                            <th>Nomor Invoice</th>
                                            <th>Tanggal</th>
                                            <th>Tanggal Jatuh Tempo</th>
                                            <th>Total</th>
                                            <th>Sisa Pembayaran</th>
                                            <th>Remark</th>
                                            {{-- <th style="width: 10%;">#</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('js')
    <script>
        var table;
        $(document).ready(function() {
            initTable();
        });

        async function initTable() {
            table = await $('#list-manage').DataTable({
                language: {
                    "paginate": {
                        'previous': 'Prev',
                        'next': 'Next'
                    }
                },
                searching: true,
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: '{!! url()->current() !!}'
                },
                columns: [{
                        data: 'vendor_name'
                    },
                    {
                        data: 'invoice_number'
                    },
                    {
                        data: 'date',
                        render: $.fn.dataTable.render.moment('DD-MM-YYYY'),
                    },
                    {
                        data: 'due_date',
                        render: $.fn.dataTable.render.moment('DD-MM-YYYY'),
                    },
                    {
                        data: 'amount',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'remaining_amount',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'remark'
                    },
                    // {
                    //     data: 'id',
                    //     render: function(data, type, row, meta) {
                    //         return `<button id="btn-edit-${data}" class="btn btn-primary btn-approve-item btn-sm" onclick="editItem(${data},'${row.vendor_id}','${row.invoice_number}','${row.date}','${row.amount}','${row.remark ?? ""}')" ><i class="fa fa-edit"></i></button>`
                    //     }
                    // },

                ],
                createdRow: function(row, data, index) {
                    if (moment().isAfter(moment(data.due_date))) {
                        $('td', row).addClass('bg-danger');
                    }
                },
            });
        }

        function refreshTable() {
            table.ajax.reload();
        }

    </script>
@endpush
