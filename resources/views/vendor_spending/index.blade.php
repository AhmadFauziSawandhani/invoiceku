<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rohim
 * Date: 8/15/2023
 * Time: 11:12 PM
 */
?>
@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Pengeluaran Vendor</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Pengeluaran Vendor</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Filter Pengeluaran Vendor</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal awal:</label>
                                    <div class="input-group date">
                                        <input type="date" class="form-control dateStart" id="dateStart" />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Akhir:</label>
                                    <div class="input-group date">
                                        <input type="date" class="form-control dateEnd" id="dateEnd" />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="input-group date" style="margin-top: 12px">
                                    <button class="btn btn-primary btn-md btn-filter"><i class="fa fa-search"></i>
                                        &nbsp;&nbsp;Filter&nbsp;&nbsp;&nbsp; </button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="input-group date" style="margin-top: 12px">
                                    <button class="btn btn-default btn-md btn-reset"><i class="fa fa-trash"></i>
                                        &nbsp;&nbsp;Reset&nbsp; </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <button type="button" class="btn btn-block btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-default"><i class="fa fa-plus"></i> Pengeluaran</button>
                                </h3>
                                <button href="#" class="btn btn-default btn-sm btn-export" style="float: right"><span
                                        class="info-box-icon bg-success"><i class="fa fa-file-excel"></i></span> Export
                                    Excel</button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor Invoice</th>
                                            <th>Nama Vendor</th>
                                            <th>Tanggal Pengeluaran</th>
                                            <th>Nominal</th>
                                            <th>Jenis Pengeluaran</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <th colspan="4" style="text-align: right"></th>
                                        <th colspan="2"></th>
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

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Pengeluaran Vendor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-vendor-spending">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Nama Vendor</label>
                                        <select class="form-control" name="vendor_id" id="vendor" style="width: 100%">
                                            <option value="">Pilih Vendor</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Nomor Invoice</label>
                                        <select class="form-control" name="invoice_number" id="invoice_number"
                                            style="width: 100%">
                                            <option value="">Pilih Invoice</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Pilih Rekening Tujuan</label>
                                        <select class="form-control" name="account_vendor" id="account_vendor"
                                            style="width: 100%">
                                            <option value="">Pilih Rekening Tujuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Tanggal Pengeluaran</label>
                                        <input type="date" class="form-control" name="spending_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Jumlah</label>
                                        <input id="amount" type="text" class="form-control" name="amount"
                                            placeholder="jumlah" oninput="this.value = this.value.replace(/[^\d-]+/g, '')">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="remaining">Sisa Pembayaran</label>
                                        <input id="remaining" type="text" class="form-control" disabled readonly
                                            name="remaining" placeholder="Sisa Pembayaran">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis Transaksi</label>
                                        <select name="spending_type" class="form-control">
                                            <option name="">Pilih Jenis Transaksi</option>
                                            <option name="Oprasional">Oprasional</option>
                                            <option name="Omset">Omset</option>
                                            <option name="Vendor">Vendor</option>
                                            <option name="Gaji">Gaji</option>
                                            <option name="Tabungan">Tabungan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remark</label>
                                        <textarea name="remark" class="form-control" rows="3" placeholder="Remark"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-submit" class="btn btn-primary btn-submit">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@push('js')
    <script>
        var table;
        var invoices = [];
        var selectedInvoice;
        $(document).ready(function() {
            initTable();
            $('#vendor').select2({
                theme: "bootstrap",
                placeholder: "Pilih Vendor",
            });
            $('#invoice_number').select2({
                theme: "bootstrap",
                placeholder: "Pilih Invoice",
            });
            $('#account_vendor').select2({
                theme: "bootstrap",
                placeholder: "Pilih Rekening Tujuan",
            });

            $('#invoice_number').on('select2:select', function(e) {
                var data = e.params.data;
                const inv = invoices.find(element => element.id == data.id)
                if (inv) $('#remaining').val(formatPrice(inv.remaining_amount) ?? 0);
                else $('#form-vendor-spending #remaining').val(0);
            });

            $(document).on('change', '#vendor', function(e) {
                e.preventDefault();
                $('#form-vendor-spending #remaining').val(0);
                let id = $(this).val();
                invoices = [];
                $.ajax({
                    type: "GET",
                    url: `{{ route('vendor-spending.invoice-vendor') }}`,
                    data: {
                        vendor_id: id
                    },
                    success: function(response) {
                        invoices = response
                        let option = "<option value=''>Pilih Invoice</option>";
                        response.forEach(element => {
                            option +=
                                `<option value="${element.id}">${element.invoice_number}</option>`;
                        });
                        $('#invoice_number').html(option);
                    }
                });

                $.ajax({
                    type: "GET",
                    url: `{{ route('vendor-spending.account-vendor') }}`,
                    data: {
                        vendor_id: id
                    },
                    success: function(response) {
                        let option = "<option value=''>Pilih Rekening Tujuan</option>";
                        response.forEach(element => {
                            option +=
                                `<option value="${element.id}">${element.account_name} (${element.account_bank}) - ${element.account_number}</option>`;
                        });
                        $('#account_vendor').html(option);
                    }
                });



            });

            $('body').on('click', '.btn-submit', function() {
                saveItem();
            });

            $('body').on('click', '.btn-approve-item', function() {
                let id = $(this).data('id');
                approveItem(id);
            });

            $(".btn-filter").click(function() {
                table.draw();
            });

            $(".btn-reset").click(function() {
                $("input[type=date]").val("")
                document.getElementById("dateStart").value = "";
                document.getElementById("dateEnd").value = "";
                table.draw();
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
                ajax: {
                    url: '{{ url('/manage/vendor-spending/list') }}',
                    data: function(d) {
                        d.dateStart = $('.dateStart').val(),
                            d.dateEnd = $('.dateEnd').val()
                    }
                },
                columns: [{
                        data: 'rownum',
                        name: 'rownum'
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'vendor_name',
                        name: 'vendor_name'
                    },
                    {
                        data: 'spending_date',
                        name: 'spending_date',
                        render: $.fn.dataTable.render.moment('DD-MM-YYYY'),
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'spending_type',
                        name: 'spending_type'
                    }
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
                    total = api
                        .column(4)
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);

                    // Update footer
                    $(api.column(0).footer()).html('TOTAL Per Page <br> TOTAL');
                    $(api.column(4).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(total) + '<br>' + $.fn
                        .dataTable.render.number('.', '', 0, 'Rp.').display(json.nominal_sum)
                    )
                },
            });
        }

        function onInputPayment(e) {
            let val = e.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
            return val

            // this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
        }

        function refreshTable() {
            table.ajax.reload();
        }

        function saveItem() {
            var btnText = $('#btn-submit').html()
            $('button').prop('disabled', true)
            $('#btn-submit').html('<i class="fa fa-spinner fa-spin" ></i> Loading')
            const data = {
                '_token': '{{ csrf_token() }}',
                '_method': 'post',
                'invoice_number': $('select[name=invoice_number]').val(),
                'account_vendor': $('select[name=account_vendor]').val(),
                'vendor_id': $('select[name=vendor_id]').val(),
                'spending_date': $('input[name=spending_date]').val(),
                'amount': $('input[name=amount]').val(),
                'spending_type': $('select[name=spending_type]').val(),
                'remark': $('textarea[name=remark]').val(),
            };
            $.ajax({
                type: "POST",
                url: '{{ url('manage/vendor-spending/store') }}',
                data: data,
                success: function(resp) {
                    $('#modal-default form')[0].reset();
                    $('#modal-default').modal('hide');
                    showSuccess(resp.msg);
                    refreshTable();
                    $('#btn-submit').html(btnText)
                    $('button').prop('disabled', false)
                },
                error: function(err) {
                    showError(err.responseJSON.messages);
                    $('#btn-submit').html(btnText)
                    $('button').prop('disabled', false)
                }
            });


            // $.post('{{ url('manage/vendor-spending/store') }}', data, res => {
            //     if (res.status == 200) {
            //         $('#modal-default form')[0].reset();
            //         $('#modal-default').modal('hide');
            //         showSuccess(res.msg);
            //         refreshTable();
            //         $('#btn-submit').html(btnText)
            //         $('button').prop('disabled', false)
            //     } else {
            //         console.log(res);
            //         showError(res.msg);
            //         $('#btn-submit').html(btnText)
            //         $('button').prop('disabled', false)
            //     }
            //     $('#btn-submit').html(btnText)
            //     $('button').prop('disabled', false)
            // })
        }

        function formatPrice(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value)
        }

        $(".btn-export").click(function(e) {
            e.preventDefault();

            var query = {
                dateStart: $('.dateStart').val(),
                dateEnd: $('.dateEnd').val()
            }

            var url = `{{ url('manage/vendor-spending/export') }}` + '?' + $.param(query)
            console.log(url);
            window.location = url;

        });
    </script>
@endpush
