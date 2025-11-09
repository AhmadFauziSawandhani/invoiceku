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
                              <div class="card-header">
                                <form id="form-filter">
                                    <div class="card-body">
                                        <div class="row d-flex align-items-center">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Nama Vendor:</label>
                                                    <div class="input-group date">
                                                        <select class="form-control" name="vendor_id" id="vendorFilter"
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
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Tanggal awal:</label>
                                                    <div class="input-group date">
                                                        <input type="date" class="form-control dateStart"
                                                            id="dateStart" />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Tanggal Akhir:</label>
                                                    <div class="input-group date">
                                                        <input type="date" class="form-control dateEnd" id="dateEnd" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Jenis Invoice:</label>
                                                    <div class="input-group date">
                                                        <select name="invoice_type" id="invoice_type" class="form-control">
                                                            <option value="">Pilih Jenis Invoice</option>
                                                            <option value="finish">Lunas</option>
                                                            <option value="unfinished">Belum Lunas</option>
                                                        </select>
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
                                             <button class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-add-vendor"><i class="fa fa-plus"></i>Tambah Invoice</button>
                                        {{-- <button class="btn btn-success btn-md mx-1" data-toggle="modal"
                                        data-target="#modelId"><i class="fa fa-upload"></i>
                                        &nbsp;&nbsp;Import&nbsp; </button> --}}
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-header -->
                            {{-- <div class="card-header">
                                <h3 class="card-title">

                                </h3>
                            </div> --}}
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th>No</th> --}}
                                            <th></th>
                                            <th>Nama Vendor</th>
                                            <th>Nomor Invoice</th>
                                            <th>Tanggal</th>
                                            <th>Tanggal Jatuh Tempo</th>
                                            <th>Omset</th>
                                            <th>Nominal Invoice (HPP)</th>
                                            <th>Sisa Pembayaran</th>
                                            <th>Profit</th>
                                            <th>Remark</th>
                                            <th style="width: 5%;">#</th>
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

    <div class="modal fade" id="modal-add-vendor">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Invoice</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-add-vendor">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="shipping_id" id="shipping_id">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vendor">Customer / Shiping</label>
                                    <select id="selectCustomer" name="shippings[]" multiple="multiple" class="form-control"
                                        style="width: 100%"></select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vendor">Vendor</label>
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
                                    <label for="exampleInputPassword1">No Resi</label>
                                    <textarea type="text" class="form-control" name="no_resi" placeholder="No Resi" id="no_resi"
                                        style="text-transform:uppercase"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nama Customer</label>
                                    <textarea type="text" class="form-control" name="nama_cs" placeholder="Nama Customer" id="nama_cs"
                                        style="text-transform:uppercase"> </textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Tujuan</label>
                                    <textarea type="text" class="form-control" name="tujuan" placeholder="Tujuan" id="tujuan"
                                        style="text-transform:uppercase"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">No Invoice Vendor</label>
                                    <input type="text" class="form-control" name="invoice_number"
                                        placeholder="No Invoice" style="text-transform:uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Tanggal Invoice</label>
                                    <input type="date" class="form-control" name="date"
                                        placeholder="Tanggal Invoice" style="text-transform:uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Tanggal Jatuh Tempo</label>
                                    <input type="date" class="form-control" name="due_date"
                                        placeholder="Tanggal Jatuh Tempo" style="text-transform:uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Omset</label>
                                    <input type="text" class="form-control" name="omset" placeholder="Omset"
                                        id="omset" style="text-transform:uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nominal Invoice Vendor (HPP)</label>
                                    <input type="text" class="form-control" name="amount" placeholder="Nominal"
                                        onkeyup="updateProfit(event)"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Profit</label>
                                    <input type="text" class="form-control" name="profit" id="profit"
                                        placeholder="profit" readonly
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="remark">Remark</label>
                                    <textarea name="remark" class="form-control"></textarea>
                                </div>
                            </div>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Invoice</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-vendor">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="shipping_id" id="shipping_id">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vendor">Customer / Shiping</label>
                                    <select id="selectCustomerEdit" name="shippings[]" multiple="multiple"
                                        class="form-control" style="width: 100%"></select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vendor">Vendor</label>
                                    <select class="form-control" name="vendor_id" id="vendor_edit" style="width: 100%">
                                        <option value="">Pilih Vendor</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">No Resi</label>
                                    <textarea type="text" class="form-control" name="no_resi" placeholder="No Resi" id="no_resi"
                                        style="text-transform:uppercase"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nama Customer</label>
                                    <textarea type="text" class="form-control" name="nama_cs" placeholder="Nama Customer" id="nama_cs"
                                        style="text-transform:uppercase"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Tujuan</label>
                                    <textarea type="text" class="form-control" name="tujuan" placeholder="Tujuan" id="tujuan"
                                        style="text-transform:uppercase"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">No Invoice Vendor</label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                        placeholder="No Invoice" style="text-transform:uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Tanggal Invoice</label>
                                    <input type="date" class="form-control" name="date" id="date"
                                        placeholder="Tanggal Invoice" style="text-transform:uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Tanggal Jatuh Tempo</label>
                                    <input type="date" class="form-control" name="due_date" id="due_date"
                                        placeholder="Tanggal Jatuh Tempo" style="text-transform:uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Omset</label>
                                    <input type="text" class="form-control" name="omset" placeholder="Omset"
                                        id="omset" style="text-transform:uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nominal Invoice Vendor (HPP)</label>
                                    <input type="text" class="form-control" name="amount" placeholder="Nominal"
                                        onkeyup="updateProfitEdit(event)" id="amount"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Profit</label>
                                    <input type="text" class="form-control" name="profit" id="profit"
                                        placeholder="profit" readonly
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="remark">Remark</label>
                                    <textarea name="remark" class="form-control" id="remark"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="submitEdit" class="btn btn-primary btn-submit">Save</button>
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

             $('#vendorFilter').select2({
                theme: "bootstrap",
                placeholder: "Pilih Vendor",
            });
            $('#vendor').select2({
                theme: "bootstrap",
                dropdownParent: $('#modal-add-vendor'),
                placeholder: "Pilih Vendor",
            });
            $('#vendor_edit').select2({
                theme: "bootstrap",
                dropdownParent: $('#modal-edit-vendor'),
                placeholder: "Pilih Vendor",
            });

            $("#selectCustomer").select2({
                theme: "bootstrap",
                dropdownParent: $('#modal-add-vendor'),
                placeholder: "Customer",
                ajax: {
                    type: "POST",
                    url: "{{ route('get-customer-invoice') }}",
                    dataType: 'json',
                    delay: 350,
                    data: function(params) {
                        return {
                            search: params.term, // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                },
                placeholder: 'Cari No Resi / No Invoice',
                minimumInputLength: 3,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

            $("#selectCustomerEdit").select2({
                theme: "bootstrap",
                dropdownParent: $('#modal-edit-vendor'),
                placeholder: "Customer",
                ajax: {
                    type: "POST",
                    url: "{{ route('get-customer-invoice') }}",
                    dataType: 'json',
                    delay: 350,
                    data: function(params) {
                        return {
                            search: params.term, // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                },
                placeholder: 'Cari No Resi / No Invoice',
                minimumInputLength: 3,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });
            $('#selectCustomer').on('change', function(e) {
                let data = $(this).select2('data')
                let resi = data.reduce(function(a, b) {
                    return a + ["", ", "][+!!a.length] + b.receipt_number;
                }, "");
                let cs = data.reduce(function(a, b) {
                    return a + ["", ", "][+!!a.length] + b.shipping_name;
                }, "");
                let tujuan = data.reduce(function(a, b) {
                    return a + ["", ", "][+!!a.length] + b.destination;
                }, "");
                let omset = data.reduce(function(a, b) {
                    return parseInt(a) + parseInt(b.sub_total)
                }, 0);

                let ppn = data.reduce(function(a, b) {
                    return parseInt(a) + parseInt(b.ppn)
                }, 0);


                $('#modal-add-vendor #no_resi').val(resi).attr('readonly', 'readonly')
                $('#modal-add-vendor #nama_cs').val(cs).attr('readonly', 'readonly')
                $('#modal-add-vendor #tujuan').val(tujuan).attr('readonly', 'readonly')
                $('#modal-add-vendor #omset').val(omset + ppn ).attr('readonly', 'readonly')
            });
            $('#selectCustomerEdit').on('change', function(e) {
                let data = $(this).select2('data')
                let resi = data.reduce(function(a, b) {
                    return a + ["", ", "][+!!a.length] + b.receipt_number;
                }, "");
                let cs = data.reduce(function(a, b) {
                    return a + ["", ", "][+!!a.length] + b.shipping_name;
                }, "");
                let tujuan = data.reduce(function(a, b) {
                    return a + ["", ", "][+!!a.length] + b.destination;
                }, "");
                let omset = data.reduce(function(a, b) {
                    return parseInt(a) + parseInt(b.sub_total) + parseInt(b.ppn)
                }, 0);

                let ppn = data.reduce(function(a, b) {
                    return parseInt(a) + parseInt(b.ppn)
                }, 0);

                $('#modal-edit-vendor #no_resi').val(resi).attr('readonly', 'readonly')
                $('#modal-edit-vendor #nama_cs').val(cs).attr('readonly', 'readonly')
                $('#modal-edit-vendor #tujuan').val(tujuan).attr('readonly', 'readonly')
                $('#modal-edit-vendor #omset').val(omset + ppn ).attr('readonly', 'readonly')
            });
            // $('#selectCustomer').on('select2:select', function(e) {
            //     var data = e.params;
            //     $('#modal-add-vendor #shipping_id').val(data.id).attr('readonly', 'readonly')
            //     $('#modal-add-vendor #no_resi').val(data.receipt_number).attr('readonly', 'readonly')
            //     $('#modal-add-vendor #nama_cs').val(data.shipping_name).attr('readonly', 'readonly')
            //     $('#modal-add-vendor #tujuan').val(data.destination).attr('readonly', 'readonly')
            //     $('#modal-add-vendor #omset').val(data.total).attr('readonly', 'readonly')

            // });
            // $('#selectCustomerEdit').on('select2:select', function(e) {
            //     var data = e.params.data;
            //     $('#modal-edit-vendor #shipping_id').val(data.id).attr('readonly', 'readonly')
            //     $('#modal-edit-vendor #no_resi').val(data.receipt_number).attr('readonly', 'readonly')
            //     $('#modal-edit-vendor #nama_cs').val(data.shipping_name).attr('readonly', 'readonly')
            //     $('#modal-edit-vendor #tujuan').val(data.destination).attr('readonly', 'readonly')
            //     $('#modal-edit-vendor #omset').val(data.total).attr('readonly', 'readonly')
            // });

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
                        d.vendor_id = $("#vendorFilter").val(),
                        d.dateStart = $('.dateStart').val(),
                        d.dateEnd = $('.dateEnd').val(),
                        d.invoice_type = $('#invoice_type').val()
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
                        data: 'due_date',
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
                        data: 'remaining_amount',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'profit',
                        render: $.fn.dataTable.render.number('.', '', 0, 'Rp.')
                    },
                    {
                        data: 'remark'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return `<button id="btn-edit-${data}" class="btn btn-primary btn-approve-item btn-sm" onclick="editItem(${data})" ><i class="fa fa-edit"></i></button>`
                        }
                    },

                ],
                createdRow: function(row, data, index) {
                    if (moment().isAfter(moment(data.due_date))) {
                        $('td', row).addClass('bg-danger');
                    }
                },
            });
        }
         function resetFilter() {
            $('#form-filter')[0].reset();
            $('#vendorFilter').val('').trigger('change');
            $('#invoice_type').val('').trigger('change');
            refreshTable();
        }
         $(".btn-reset").click(function() {
            resetFilter();
        });
        $("#form-filter").submit(function(e) {
            e.preventDefault();
            refreshTable();
        })

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
            }else{
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


        function formatRepo(repo) {
            if (repo.loading) {
                return repo.text;
            }

            var $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "</div>" +
                "</div>" +
                "</div>"
            );

            $container.find(".select2-result-repository__title").text(repo.invoice_number);
            $container.find(".select2-result-repository__description").text('No Resi: ' + repo.invoice_number);

            return $container;
        }

        function formatRepoSelection(repo) {
            return repo.invoice_number || repo.text;
        }

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

        function updateProfit(e) {
            let omset = $('#modal-add-vendor #omset').val()
            $('#modal-add-vendor #profit').val(parseInt(omset) - parseInt(e.target.value))
        }

        function updateProfitEdit(e) {
            let omset = $('#modal-edit-vendor #omset').val()
            $('#modal-edit-vendor #profit').val(parseInt(omset) - parseInt(e.target.value))
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

        async function editItem(id) {
            let urlShow = "{{ route('invoice.show', ':id') }}";
            urlShow = urlShow.replace(':id', id);
            let detailData = null
            let shipingData = []
            $.ajax({
                type: "GET",
                url: urlShow,
                success: function(response) {
                    detailData = response
                    Object.entries(response).forEach(([key, value]) => {
                        $('#modal-edit-vendor #' + key).val(value);
                    })
                    $('#vendor_edit').val(response.vendor_id);
                    $('#vendor_edit').trigger('change')

                }
            });



            $('#modal-edit-vendor').modal('show');
            var url = "{{ route('invoice.update', ':id') }}";
            url = url.replace(':id', id);
            let custId = null;
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
                            setTimeout(() => {
                                window.location.reload()
                            }, 1500);
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
    </script>
@endpush
