@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Invoice Vendor</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Invoice Vendor</li>
                        </ol>
                    </div>
                </div>

                <div class="card">
                    <form id="form-filter">
                        <div class="card-body">
                            <div class="row d-flex align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Vendor:</label>
                                        <div class="input-group date">
                                            <select class="form-control" name="vendor_id" id="vendor"
                                                style="width: 100%">
                                                <option value="">Pilih Vendor</option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal awal:</label>
                                        <div class="input-group date">
                                            <input type="date" class="form-control dateStart" id="dateStart" />
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Akhir:</label>
                                        <div class="input-group date">
                                            <input type="date" class="form-control dateEnd" id="dateEnd" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.row -->
                        </div>
                        <div class="card-footer d-flex ">
                            <button type="submit" class="btn btn-primary btn-md mx-1 btn-filter"><i
                                    class="fa fa-search"></i>
                                &nbsp;&nbsp;Filter&nbsp;&nbsp;&nbsp; </button>
                            <button class="btn btn-default btn-md mx-1 btn-reset"><i class="fa fa-trash"></i>
                                &nbsp;&nbsp;Reset&nbsp; </button>
                            {{-- <button class="btn btn-success btn-md mx-1" data-toggle="modal"
                                                data-target="#modelId"><i class="fa fa-upload"></i>
                                                &nbsp;&nbsp;Import&nbsp; </button> --}}
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="omset">100000</h3>
                                <p>Total Omset <span id="date"></span></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-dollar-sign"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="hpp">100000</h3>
                                <p>Total HPP <span id="date2"></span>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-dollar-sign"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="profit">100000</h3>
                                <p>Total Profit <span id="date3"></span>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-dollar-sign"></i>
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

                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th>No</th> --}}
                                            <th></th>
                                            <th>Nama Vendor</th>
                                            <th>Nomor Invoice</th>
                                            <th>Tanggal</th>
                                            <th>Omset</th>
                                            <th>Nominal Invoice (HPP)</th>
                                            <th>Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-center">Total</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
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
            $('#vendor').select2({
                theme: "bootstrap",
                placeholder: "Pilih Vendor",
            });
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
                ordering: false,
                ajax: {
                    url: '{!! url()->current() !!}',
                    data: function(d) {
                        d.vendor_id = $("#vendor").val()
                        d.dateStart = $('.dateStart').val()
                        d.dateEnd = $('.dateEnd').val()
                    },
                },
                columns: [{
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
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
                        data: 'omset',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'amount',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'profit',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },

                ],
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();
                    var json = table.ajax.json();
                    // Remove the formatting to get integer data for summation
                    let intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i :
                            0;
                    };
                    $('#omset').html($.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json
                        .totalOmset))
                    $('#hpp').html($.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalHpp))
                    $('#profit').html($.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json
                        .totalProfit))

                    if ($('.dateStart').val()) {
                        $('#date,#date2,#date3').html('per Tanggal: ' + $('.dateStart').val() + ' - ' + $('.dateEnd').val())
                    }
                    // Update footer
                    $(api.column(0).footer()).html('TOTAL');
                    $(api.column(4).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalOmset)
                    )
                    $(api.column(5).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalHpp)
                    )
                    $(api.column(6).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalProfit ??
                            0)
                    )
                },
            });
        }

        function format(d) {
            // `d` is the original data object for the row
            if (d.shippings.length > 0) {
                let content = ''
                let total = 0
                let thead = `<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No Resi</th>
                                <th>Tanggal Invoice</th>
                                <th>Nama Customer</th>
                                <th>Tujuan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody> `;

                d.shippings.forEach(element => {
                    content += `<tr>
                                <td>${element.receipt_number}</td>
                                <td>${element.shipping.invoice_date}</td>
                                <td>${element.shipping_name}</td>
                                <td>${element.destination}</td>
                                <td>${formatPrice(element.total)}</td>
                            </tr>`
                    total += parseInt(element.total)
                })

                let tbody = `</tbody>
                        <tfoot></tfoot>
                            <tr>
                                <th colspan="3" style="text-align: right">Total</th>
                                <th>${formatPrice(total)}</th>
                            </tr>
                        </tfoot>
                        </table>`;
                return thead + content + tbody
            } else {
                return '<p class="text-center">Belum ada data</p>'
            }
        }

        $('#list-manage').on('click', 'td.dt-control', function(e) {
            let tr = e.target.closest('tr');
            let row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
            } else {
                // Open this row
                row.child(format(row.data())).show();
            }
        });

        function resetFilter() {
            $('#form-filter')[0].reset();
            $('#vendor').val('').trigger('change');
            refreshTable();
        }
        $(".btn-reset").click(function() {
            resetFilter();
        });
        $("#form-filter").submit(function(e) {
            e.preventDefault();
            refreshTable();
        })

        function refreshTable() {
            table.ajax.reload();
        }

        function formatPrice(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value)
        }
    </script>
@endpush
