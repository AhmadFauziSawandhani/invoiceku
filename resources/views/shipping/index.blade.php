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
            @if ($message = Session::get('success'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>
                        <p>{{ $message }}</p>
                    </strong>
                </div>
            @endif
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Input Invoice</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Input Invoice</li>
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
                        <h3 class="card-title">Filter Invoice</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-2">
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
                            <div class="col-md-2">
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
                            <!-- /.col -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Berdasarkan:</label>
                                    <div class="input-group date">
                                        <select name="filterBy" id="filterBy" class="form-control filterBy">
                                            <option value="">Pilih Filter Berdasarkan</option>
                                            <option value="1">Cash</option>
                                            <option value="2">TOP</option>
                                            <option value="3">Belum Bayar</option>
                                            <option value="4">Jatuh Tempo</option>
                                            <option value="5">Invoice Laut</option>
                                            <option value="6">Invoice Udara</option>
                                            <option value="7">Invoice Kendaraan</option>
                                        </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-sort"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Nomor Telepon Pengirim:</label>
                                    <div class="input-group date">
                                        <input type="number" class="form-control shipperPhoneNumber"
                                            id="shipperPhoneNumber" />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-phone"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Nama Marketing</label>
                                    <select id="sales_name" class="form-control">
                                        <option value="">Semua Marketing</option>
                                        <option value="IBU DYTA">IBU DYTA</option>
                                        <option value="IBU AINUN">IBU AINUN</option>
                                        <option value="IBU FIRA">IBU FIRA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="input-group date" style="margin-top: 8px">
                                    <button class="btn btn-primary btn-md btn-filter"><i class="fa fa-search"></i>
                                        &nbsp;&nbsp;Filter&nbsp;&nbsp;&nbsp; </button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="input-group date" style="margin-top: 8px">
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
                                    <a href="{{ route('shipping.create-invoice') }}"
                                        class="btn btn-block btn-primary btn-sm"><i class="fa fa-plus"></i> Input
                                        Invoice</a>
                                </h3>
                                <button href="#" class="btn btn-default btn-sm btn-export" style="float: right"><span
                                        class="info-box-icon bg-success"><i class="fa fa-file-excel"></i></span> Export
                                    Excel</button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="list-manage" class="table table-bordered table-hover" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No Invoice</th>
                                                <th>Marketing</th>
                                                <th>Tanggal Invoice</th>
                                                <th>No Resi</th>
                                                <th>Nama Pengirim</th>
                                                <th>No Telepon</th>
                                                <th>Destinasi</th>
                                                <th>PPN</th>
                                                <th>Jumlah</th>
                                                <th>DP</th>
                                                <th>Sisa Bayar</th>
                                                <th>Jenis Pembayaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="8" style="text-align:right">Total:</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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

    <!-- Modal -->
    <div class="modal fade" id="imgModal" tabindex="-1" role="dialog" aria-labelledby="imgModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Image Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card card-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="img-inv">

                        </tbody>
                    </table>
                </div>
                <form action="{{ route('shipping.upload-inv-img') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="shipping_id" id="shipping_id">
                        <div class="form-group">
                            <label for="">Pilih Image</label>
                            <input type="file" accept="image/png" class="form-control-file" name="image"
                                id="image" placeholder="Pilih Image" aria-describedby="fileHelpId">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="vue-dialog">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Pembayaran Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-verification">
                    <div class="modal-body">
                        <input type="hidden" name="shipping_id" id="shipping_id">
                        <div class="card-body pt-1">
                            <label>Tipe Pembayaran</label>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_type"
                                        v-model="payment_type" id="down_payment" value="down_payment">
                                    <label class="form-check-label" for="down_payment">DP</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_type"
                                        v-model="payment_type" id="repayment" value="repayment">
                                    <label class="form-check-label" for="repayment">Pelunasan</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pembayaran Invoice</label>
                                <input type="date" required="required" class="form-control" name="payment_date"
                                    placeholder="Tanggal Pembayaran Invoice">
                            </div>
                            <div class="form-group" v-if="payment_type == 'repayment'">
                                <label>Pos Keuangan</label>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-check">
                                            <input type="checkbox" id="vendor" name="vendor" value="1"
                                                onclick="percentage()" class="form-check-input">
                                            <label class="form-check-label" for="vendor">Vendor</label>
                                        </div>
                                        <div class="form-check ml-2">
                                            <input type="checkbox" id="operational" name="operational" value="2"
                                                onclick="percentage()" class="form-check-input">
                                            <label class="form-check-label" for="operational">Operasional</label>
                                        </div>
                                        <div class="form-check ml-2">
                                            <input type="checkbox" id="salary" name="salary" value="3"
                                                onclick="percentage()" class="form-check-input">
                                            <label class="form-check-label" for="salary">Gaji</label>
                                        </div>
                                        <div class="form-check ml-2">
                                            <input type="checkbox" type="checkbox" id="saving" name="saving"
                                                value="3" onclick="percentage()" class="form-check-input">
                                            <label class="form-check-label" for="saving">Tabungan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="total_pos" id="total_pos">
                            <div class="row" v-if="payment_type == 'repayment'">
                                <div class="col-md-3">
                                    <div class="form-group" id="checked_vendor" style="display:none;">
                                        <label>Persentase Vendor</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control vendor_percentage"
                                                id="vendor_percentage" name="vendor_percentage"
                                                placeholder="Persentase Vendor"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" id="checked_operational" style="display:none;">
                                        <label>Persentase Operasional</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control operational_percentage"
                                                id="operational_percentage" name="operational_percentage"
                                                placeholder="Persentase Operasional"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" id="checked_salary" style="display:none;">
                                        <label>Persentase Gaji</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control salary_percentage"
                                                id="salary_percentage" name="salary_percentage"
                                                placeholder="Persentase Gaji"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" id="checked_saving" style="display:none;">
                                        <label>Persentase Tabungan</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control saving_percentage"
                                                id="saving_percentage" name="saving_percentage"
                                                placeholder="Persentase Tabungan"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" v-if="payment_type == 'repayment'">
                                <label for="total_pay">Berapa kali bayar: </label>
                                <input type="text" name="total_pay" v-model="total_pay" id="total_pay"
                                    class="form-control" placeholder="" aria-describedby="helpId">
                            </div>
                            <div class="col-lg-12" v-show="payment_type == 'repayment'">
                                <div class="row" v-for="(item, index) in payDetails">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Nominal ke @{{ index + 1 }}</label>
                                            <input type="text" name="nominal" v-model="payDetails[index].nominal"
                                                :id="`nominal-${index}`" class="form-control" placeholder=""
                                                aria-describedby="helpId"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Bank Tujuan ke @{{ index + 1 }}</label>
                                            {{-- <input type="text" name="bank" id="bank" v-model="payDetails[index].bank" --}}
                                            {{-- class="form-control" placeholder="" aria-describedby="helpId"> --}}
                                            <select name="bank" id="bank" v-model="payDetails[index].bank"
                                                class="form-control" aria-describedby="helpId">
                                                <option value="">Pilih Bank</option>
                                                <option value="Mandiri">Mandiri</option>
                                                <option value="BCA">BCA</option>
                                                <option value="Cash">Cash</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12" v-show="payment_type == 'down_payment'">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Nominal</label>
                                            <input type="text" name="nominal" v-model="nominal" id="nominal"
                                                class="form-control" placeholder="" aria-describedby="helpId"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Bank Tujuan </label>
                                            {{-- <input type="text" name="bank" id="bank" v-model="payDetails[index].bank" --}}
                                            {{-- class="form-control" placeholder="" aria-describedby="helpId"> --}}
                                            <select name="bank" id="bank" v-model="bank" class="form-control"
                                                aria-describedby="helpId">
                                                <option value="">Pilih Bank</option>
                                                <option value="Mandiri">Mandiri</option>
                                                <option value="BCA">BCA</option>
                                                <option value="Cash">Cash</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary btn-approve">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-confirm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Konfirmasi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin akan memproses data invoice ini ke bagian tracking / pengiriman?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-confirm" class="btn btn-primary">Ya, Ubah</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
@endsection

@push('js')
    <script>
        var table;
        $(document).ready(function() {
            initTable();

            $('body').on('click', '.btn-submit', function() {
                saveItem();
            });

            $('body').on('click', '.btn-approve', function() {
                let id = $(this).data('id');
                approveItem(id);
            });

            $('body').on('click', '.btn-print-invoice', function() {
                let id = $(this).data('id');
                printInvoice(id);
            });

            $(".btn-filter").click(function() {
                table.draw();
            });

            $(".btn-reset").click(function() {
                $("input[type=date]").val("")
                document.getElementById("dateStart").value = "";
                document.getElementById("dateEnd").value = "";
                $('#filterBy').prop('selectedIndex', 0)
                document.getElementById("shipperPhoneNumber").value = "";
                table.draw();
            });

            $('body').on('click', '.btn-notif', function() {
                $('#id_shipping').val($(this).data('id'));
            })
        });

        function percentage() {
            let total_pos = 0;
            if (document.getElementById('vendor').checked) {
                $('#checked_vendor').show()
                total_pos = total_pos + 1;
            } else {
                $('#checked_vendor').hide()
            }
            if (document.getElementById('operational').checked) {
                $('#checked_operational').show()
                total_pos += 1;
            } else {
                $('#checked_operational').hide()
            }
            if (document.getElementById('salary').checked) {
                $('#checked_salary').show()
                total_pos += 1;
            } else {
                $('#checked_salary').hide()
            }
            if (document.getElementById('saving').checked) {
                $('#checked_saving').show()
                total_pos += 1;
            } else {
                $('#checked_saving').hide()
            }
            console.log(total_pos)
            $('input[name=total_pos]').val(total_pos)
        }

        const IDR = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number);
        }

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
                    url: '{{ url('/manage/shipping/list') }}',
                    data: function(d) {
                        d.dateStart = $('.dateStart').val(),
                            d.dateEnd = $('.dateEnd').val(),
                            d.filterBy = $('.filterBy').val(),
                            d.shipperPhoneNumber = $('.shipperPhoneNumber').val()
                        d.sales_name = $('#sales_name').val()
                    }
                },
                fixedColumns: {
                    leftColumns: 3,
                    start: 1

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
                        data: 'sales_name',
                        name: 'sales_name'
                    },
                    {
                        data: 'invoice_date',
                        name: 'invoice_date',
                        render: $.fn.dataTable.render.moment('DD-MM-YYYY'),
                    },
                    {
                        data: 'receipt_number',
                        name: 'receipt_number'
                    },
                    {
                        data: 'shipping_name',
                        name: 'shipping_name'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'destination',
                        name: 'destination'
                    },
                    {
                        data: 'ppn',
                        name: 'ppn',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'sub_total',
                        name: 'sub_total',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'down_payment',
                        name: 'down_payment',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'remaining_payment',
                        name: 'remaining_payment',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'payment_name',
                        name: 'payment_name'
                    },
                    {
                        data: 'action',
                        name: 'action'
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
                    ppn = api
                        .column(8)
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    total = api
                        .column(9)
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    dp_sum = api
                        .column(10)
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    rm_sum = api
                        .column(11)
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    // jumlah = api
                    //     .column(11)
                    //     .data()
                    //     .reduce((a, b) => intVal(a) + intVal(b), 0);

                    // Update footer
                    $(api.column(0).footer()).html('TOTAL Per Page <br> TOTAL');
                    $(api.column(8).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(ppn) + '<br>' + $.fn
                        .dataTable.render.number('.', '', 0, 'Rp.').display(json.ppn_sum)
                    )
                    $(api.column(9).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(total) + '<br>' + $.fn
                        .dataTable.render.number('.', '', 0, 'Rp.').display(json.total_sum)
                    )
                    $(api.column(10).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(dp_sum) + '<br>' + $.fn
                        .dataTable.render.number('.', '', 0, 'Rp.').display(json.dp_sum)
                    )
                    $(api.column(11).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(rm_sum) + '<br>' + $.fn
                        .dataTable.render.number('.', '', 0, 'Rp.').display(json.rm_sum)
                    )
                    // $( api.column( 11 ).footer() ).html(
                    //     $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(jumlah)+'<br>'+$.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.total_sum)
                    // )
                },
            });
        }

        function refreshTable() {
            table.ajax.reload();
        }

        function saveItem() {
            const form = $('#form-add-invoice').serializeObject()
            $.post('{{ url('manage/shipping/store') }}', form, res => {
                if (res.status == 200) {
                    $('#modal-xl form')[0].reset();
                    $('#modal-xl').modal('hide');
                    showSuccess(res.msg);
                    refreshTable();
                } else {
                    showError(res.msg);
                }
            })
        }

        $.fn.serializeObject = function() {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };

        function approveItem(id) {
            Swal.fire({
                title: 'Yakin setujui invoice ini?',
                text: 'Anda akan menyetujui pembayaran invoice ini. Invoice yang telah diverifikasi tidak dapat dibatalkan. Lanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Tutup',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                const form_value = $('#form-verification').serializeObject()
                if (result.isConfirmed) {
                    const data = {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'post',
                        'id': id,
                        ...form_value,
                        payment_type: vueDialog.payment_type,
                        total_pay: vueDialog.payment_type == 'down_payment' ? null : vueDialog.total_pay,
                        detail_payment: vueDialog.payment_type == 'down_payment' ? [] : vueDialog.payDetails
                    };
                    $.post('{{ url('manage/shipping/approve') }}', data, res => {
                        if (res.status == 200) {
                            showSuccess(res.msg);
                            $('#approveModal form')[0].reset();
                            $('#approveModal').modal('hide');
                            refreshTable();
                        } else {
                            showError(res.msg);
                        }
                    })
                }
            })
        }

        function printInvoice(id) {
            const data = {
                '_token': '{{ csrf_token() }}',
                '_method': 'post',
                'id': id
            };
            $.post('{{ url('manage/shipping/print-invoice') }}', data, res => {
                if (res.status == 200) {
                    refreshTable();
                } else {
                    showError(res.msg);
                }
            })
        }

        $('body').delegate('.btnImgDt', 'click', function(e) {
            $('#img-inv').empty();
            let shipping_id = $(this).data('id')
            let images = ($(this).data('images'))

            images.forEach((val) => {
                $('#img-inv').append(
                    `<tr><td><img src="{{ asset('storage') . '/' }}${val.image}" style="width: 100%"></td><td><button class="btn btn-danger btn-sm ImgDelete" onclick="delete_img(${val.id})"><i class="fa fa-trash"></i></button></td></tr>`
                    );
            })

            $('#imgModal #shipping_id').val(shipping_id)
            $('#imgModal').modal('show')
        })

        function delete_img(id) {
            Swal.fire({
                title: 'Yakin mengahpus gambar ini?',
                text: 'Anda akan menghapus gambar ini. Gambar yang telah diapus tidak dapat dibatalkan. Lanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Tutup',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    const data = {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'post',
                        'id': id
                    };
                    $.post('{{ url('manage/shipping/delete-images') }}', data, res => {
                        if (res.status == 200) {
                            showSuccess(res.msg);
                            $('#imgModal').modal('hide')
                            refreshTable();
                        } else {
                            showError(res.msg);
                        }
                    })
                }
            })
        }

        var remaining = 0

        var vueDialog = new Vue({
            el: '#vue-dialog',
            data() {
                return {
                    total_pay: 1,
                    payment_type: 'repayment',
                    payDetails: [{
                        nominal: remaining,
                        bank: ""
                    }],
                    nominal: null,
                    bank: null
                }
            },
            watch: {
                total_pay: function(val) {
                    this.payDetails = []
                    if (val != null && val > 1) {
                        for (let i = 0; i < val; i++) {
                            this.payDetails.push({
                                nominal: 0,
                                bank: ""
                            })
                        }
                    }
                }
            }
        });

        $('body').delegate('.btnApprove', 'click', function(e) {
            let shipping_id = $(this).data('id')
            let remaining_payment = $(this).data('remaining');

            remaining = remaining_payment
            vueDialog.payDetails[0].nominal = remaining_payment
            $('#approveModal #shipping_id').val(shipping_id)
            // $('#approveModal #nominal-0').val(remaining_payment)
            $('#approveModal').modal('show')
        })

        $('body').delegate('.btnCancel', 'click', function(e) {
            let shipping_id = $(this).data('id')

            Swal.fire({
                title: 'Yakin membatalkan invoice ini?',
                text: 'Anda akan membatalkan invoice ini. Invoice yang telah dibatalkan akan dihapus dari sistem. Lanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Tutup',
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'post',
                        'id': shipping_id
                    };
                    $.post('{{ url('manage/shipping/delete-invoice') }}', data, res => {
                        if (res.status == 200) {
                            showSuccess(res.msg);
                            refreshTable();
                        } else {
                            showError(res.msg);
                        }
                    })
                }
            })
        })

        $('body').delegate('.btnDelPayment', 'click', function(e) {
            let shipping_id = $(this).data('id')

            Swal.fire({
                title: 'Yakin akan menghapus payment invoice ini?',
                text: 'Anda akan menghapus payment invoice ini. Lanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Tutup',
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'post',
                        'shipping_id': shipping_id
                    };
                    $.post('{{ url('manage/shipping/delete-payment') }}', data, res => {
                        if (res.status == 200) {
                            showSuccess(res.msg);
                            refreshTable();
                        } else {
                            showError(res.msg);
                        }
                    })
                }
            })
        })

        $('body').on('click', '.btn-notif', function(e) {
            var id_shipping = $(this).data('id');
            Swal.fire({
                title: 'Kirim Notifikasi Pembayaran?',
                text: 'Anda akan mengirim notifikasi kepada pelanggan. Lanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Tutup',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: `{{ route('shipping.send-notification') }}`,
                        data: {
                            id: id_shipping,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            console.log('success');
                            console.log(response);
                        },
                        error: function(response) {
                            showError(response.responseJSON.msg);
                        }
                    });

                }
            })
        })


        $(".btn-export").click(function(e) {
            e.preventDefault();

            var query = {
                dateStart: $('.dateStart').val(),
                dateEnd: $('.dateEnd').val(),
                filterBy: $('.filterBy').val(),
                shipperPhoneNumber: $('.shipperPhoneNumber').val(),
                sales_name: $('#sales_name').val()
            }

            var url = `{{ url('manage/shipping/shipping-export') }}` + '?' + $.param(query)

            window.location = url;

        });

        $('body').on('click', '.btnProses', function() {
            var id = $(this).data('id');
            let url = "{{ route('shipping.update-ready-packing', ':id') }}";
            url = url.replace(':id', id);
            $('#modal-confirm').modal('show');
            $('#modal-confirm').on('shown.bs.modal', function() {
                $('#btn-confirm').click(function() {
                    $('button').prop('disabled', true)
                    btnText = $('#btn-confirm').html()
                    $('#btn-confirm').html('<i class="fa fa-spinner fa-spin"></i> Loading')
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            ready_packing: 1
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                $('#modal-confirm').modal('hide');
                                showSuccess(response.msg);
                                setTimeout(() => {
                                    window.location.reload()
                                }, 2000);
                            } else {
                                showError(response.msg);
                            }
                        }
                    }).always(function() {
                        $('#btn-confirm').html(btnText)
                        $('button').prop('disabled', false)
                    });
                });
            });
        });
    </script>
@endpush
