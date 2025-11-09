@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Dashboard Invoice Vendor</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard Invoice Vendor</li>
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
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                 <h3>{{ (new \App\Helpers\GeneralHelpers())->currency($profit) }}</h3>

                                <p>Profit {{ strtoupper((new \App\Helpers\GeneralHelpers())->convert_date_name_month(date('Y-m-d'))) . ' ' . date('Y') }}
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
                            <div class="card-header">
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
                                                        <input type="date" class="form-control dateStart"
                                                            id="dateStart" />
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
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th>No</th> --}}
                                            <th>Nama Vendor</th>
                                            <th>Nomor Invoice</th>
                                            <th>Tipe</th>
                                            <th>Tanggal</th>
                                            <th>Tanggal Jatuh Tempo</th>
                                            <th>Jumlah</th>
                                            <th>Remark</th>
                                            <th class="text-center" style="width: 5%;">#</th>
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

    <div class="modal fade" id="modal-edit-color">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Warna</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-color">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" id="id" name="id">
                            <label for="color">Pilih Warna</label>
                            <input type="color" id="color" class="form-control" name="color" placeholder="Pilih Warna"
                                style="text-transform:uppercase">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-submit">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-add-vendor">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Invoice</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-add-vendor">
                    <div class="modal-body">
                        <label for="vendor">Vendor</label>
                        <div class="form-group">
                            <select class="form-control" name="vendor_id" id="vendor" style="width: 100%">
                                <option value="">Pilih Vendor</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">No Invoice</label>
                            <input type="text" class="form-control" name="invoice_number" placeholder="No Invoice"
                                style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Tanggal Invoice</label>
                            <input type="date" class="form-control" name="date" placeholder="Tanggal Invoice"
                                style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Tanggal Jatuh Tempo</label>
                            <input type="date" class="form-control" name="due_date" placeholder="Tanggal Jatuh Tempo"
                                style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nominal</label>
                            <input type="text" class="form-control" name="amount" placeholder="Nominal"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        </div>
                        <div class="form-group">
                            <label for="remark">Remark</label>
                            <textarea name="remark" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-submit">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-edit-vendor">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Vendor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-vendor">
                    <div class="modal-body">
                        <div class="modal-body">
                            <label for="vendor">Vendor</label>
                            <div class="form-group">
                                <select class="form-control" name="vendor_id" id="vendor_edit" style="width: 100%">
                                    <option value="">Pilih Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">No Invoice</label>
                                <input id="invoice_number" type="text" class="form-control" name="invoice_number"
                                    placeholder="No Invoice" style="text-transform:uppercase">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Tanggal Invoice</label>
                                <input id="date" type="date" class="form-control" name="date"
                                    placeholder="Tanggal Invoice" style="text-transform:uppercase">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Tanggal Jatuh Tempo</label>
                                <input type="date" class="form-control" name="due_date"
                                    placeholder="Tanggal Jatuh Tempo" style="text-transform:uppercase">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Nominal</label>
                                <input id="amount" type="text" class="form-control" name="amount"
                                    placeholder="Nominal"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                            </div>
                            <div class="form-group">
                                <label for="remark">Remark</label>
                                <textarea name="remark" id="remark" class="form-control"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" id="submitEdit" class="btn btn-primary btn-submit">Save</button>
                        </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
                        data: 'vendor.name',
                        render: function(data, type, row, meta) {
                            return data ?? '-';
                        }
                    },
                    {
                        data: 'invoice_no'
                    },
                    {
                        data: 'type'
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
                        data: 'remark'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return `<button class="btn btn-primary btn-edit-color btn-sm" data-id="${data}" data-color="${row.color}"><i class="fa fa-eye-dropper"></i></button>`
                        }
                    },

                ],
                createdRow: function(row, data, index) {
                    if(data.color){
                        $('td', row).css('background-color', data.color);
                        if (moment().isAfter(moment(data.due_date))) {
                            $('td', row).addClass('text-danger');
                        }
                    }else{
                        if (moment().isAfter(moment(data.due_date))) {
                            $('td', row).addClass('text-danger');
                        }
                    }
                },
            });
        }

        function refreshTable() {
            table.ajax.reload();
        }

        $("#form-add-vendor").submit(function(e) {
            e.preventDefault();
            e.stopPropagation();

            $.ajax({
                type: "POST",
                url: "{{ route('invoice.store') }}",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == 200) {
                        $('#modal-add-vendor form')[0].reset();
                        $('#modal-add-vendor').modal('hide');
                        showSuccess(response.msg);
                        refreshTable();
                    } else {
                        showError(response.msg);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    var err = JSON.parse(xhr.responseText);
                    showError(err.messages);
                }
            });
        });

        function editItem(id, name, inv, date, amount, remark) {
            $('#modal-edit-vendor').modal('show');
            $('#vendor_edit').val(name);
            $('#vendor_edit').trigger('change');
            $('#invoice_number').val(inv);
            $('#date').val(date);
            $('#amount').val(amount);
            $('#remark').val(remark);
            var url = "{{ route('invoice.update', ':id') }}";
            url = url.replace(':id', id);
            $("#submitEdit").click(function(e) {
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    type: "PUT",
                    url: url,
                    data: $("#form-edit-vendor").serialize(),
                    success: function(response) {
                        if (response.status == 200) {
                            $('#modal-edit-vendor form')[0].reset();
                            $('#modal-edit-vendor').modal('hide');
                            showSuccess(response.msg);
                            refreshTable();
                        } else {
                            showError(response.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        var err = JSON.parse(xhr.responseText);
                        showError(err.messages);
                    }
                });
            })
        }
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

        $('body').delegate('.btn-edit-color', 'click', function() {
            let id = $(this).data('id');
            let color = $(this).data('color');
            $('#modal-edit-color').modal('show');
            $('#modal-edit-color #id').val(id);
            $('#modal-edit-color #color').val(color);

            $("#form-edit-color").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('update-color') }}",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 200) {
                            $('#modal-edit-color form')[0].reset();
                            $('#modal-edit-color').modal('hide');
                            showSuccess(response.msg);
                            setTimeout(() => {
                                window.location.reload()
                            }, 1500);
                        } else {
                            showError(response.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        showError(err.message);
                    }
                });
            })
        })
    </script>
@endpush
