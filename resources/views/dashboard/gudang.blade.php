@extends('layout.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">TANGGAL {{ (new \App\Helpers\GeneralHelpers())->date_format_id(date('Y-m-d')) }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard Gudang</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-lime">
                            <div class="inner">
                                <h3>{{$manifestHariIni}}</h3>

                                <p>Data Manifest Hari ini</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{$totalManifestBulanIni}}</h3>

                                <p>Data Manifest Bulan Ini  {{ strtoupper((new \App\Helpers\GeneralHelpers())->convert_date_name_month(date('Y-m-d'))).' '.date('Y') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{$notSend}}</h3>

                                <p>Manifest Mengendap di gudang</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <!-- ./col -->
                    {{-- <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ (new \App\Helpers\GeneralHelpers())->currency($monthly->sum('saving_account'))  }}</h3>

                                <p>REKENING TABUNGAN {{ strtoupper((new \App\Helpers\GeneralHelpers())->convert_date_name_month(date('Y-m-d'))).' '.date('Y') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div> --}}
                </div>
                <!-- /.row -->
                <!-- Main row -->

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

        <!-- content invoice -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Manifest</h3>
                            </div>
                            <div class="card-header">
                                <!-- /.card-header -->
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tanggal awal:</label>
                                            <div class="input-group date">
                                                <input type="date" class="form-control start_date" id="start_date" />
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
                                                <input type="date" class="form-control end_date" id="end_date" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status Gudang</label>
                                            <select name="status" class="form-control" id="statusGudang">
                                                <option value="all">Pilih Status</option>
                                                <option value="0">Belum Keluar Gudang</option>
                                                <option value="1">Sudah Keluar Gudang</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    {{-- <div class="col-md-2">
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
                                    </div> --}}
                                    <!-- /.col -->
                                    {{-- <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Nomor Telepon Pengirim:</label>
                                            <div class="input-group date">
                                                <input type="number" class="form-control shipperPhoneNumber" id="shipperPhoneNumber" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fa fa-phone"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
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
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>No STT</th>
                                        <th>Tanggal</th>
                                        <th>Marketing</th>
                                        <th>Tujuan</th>
                                        <th>Nama Pengirim</th>
                                        <th>Asal</th>
                                        <th>Koli</th>
                                        <th>Kilo/Volume</th>
                                        <th>Tgl Keluar Gudang</th>
                                        <th>Total Hari Mengendap</th>
                                        <th>Vendor</th>
                                        {{-- <th>#</th> --}}
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
        // var table;
        $(document).ready(function() {
            $(".btn-filter").click(function() {
                table.draw();
            });
            initTable();
            $(".btn-reset").click(function() {
                $("input[type=date]").val("")
                document.getElementById("start_date").value = "";
                document.getElementById("end_date").value = "";
                $('#statusGudang').prop('selectedIndex', 0)
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
                    url: "{{route('manifest-barang')}}",
                     data: function(d) {
                        d.start_date = $('.start_date').val(),
                            d.end_date = $('.end_date').val(),
                            d.status = $('#statusGudang').val()
                    }
                },
                columns: [
                    {
                        data: 'receipt_number',
                        name: 'receipt_number',
                    },
                    {
                        data: 'date_manifest',
                        name: 'date_manifest',
                    },
                    {
                        data: 'sales_name',
                        name: 'sales_name'
                    },
                    {
                        data: 'destination',
                        name: 'destination',
                    },
                    {
                        data: 'shipping_name',
                        name: 'shipping_name'
                    },
                    {
                        data: 'shipping_city',
                        name: 'shipping_city'
                    },
                    {
                        data: 'total_colly',
                        name: 'total_colly'
                    },
                    {
                        data: 'total_actual',
                        name: 'total_actual',
                        render: function(data, type, row) {
                            return `${data} kg / ${row.total_volume}`
                        }
                    },
                    {
                        data: 'warehouse_exit_date',
                        name: 'warehouse_exit_date',
                    },
                    {
                        data: 'date_manifest',
                        name: 'date_manifest',
                        render: function(data, type, row) {
                            let exitDate = row.warehouse_exit_date ? moment(row.warehouse_exit_date) : null;
                            let totalDay = moment(exitDate ?? moment()).diff(moment(data), 'days');
                            let fromNow = moment(data).fromNow().replace('days', 'hari').replace('hours', 'jam').replace('minutes', 'menit').replace('seconds', 'detik').replace('a day', '1 hari').replace('a month', '1 bulan').replace('a year', '1 tahun').replace('a year', '1 tahun').replace('a week', '1 minggu').replace('a week', '1 minggu').replace('ago', '');
                            return exitDate ? totalDay + ' Hari' : fromNow;
                        }
                    },
                    {
                        data: 'vendor_name',
                        render: function(data,type,row,meta){
                            return data ? data : row.vendor_typ ?? '-';
                        }
                    },
                    // {
                    //     data: 'id',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function(data, type, row) {
                    //         if(row.status == 0) {
                    //             return `<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                    //             <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    //             <button data-id="${data}"  class="btn btn-success btn-approve-item btn-sm"><i class="fa fa-check"></i></button>`
                    //         }else{
                    //             return `<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>`
                    //         }
                    //     }
                    // }

                ],
                createdRow: function(row, data, index) {
                    if (!data.warehouse_exit_date) {
                        let totalDay = moment(moment()).diff(moment(data.date_manifest), 'days');
                        if (totalDay >= 2) {
                            $('td', row).addClass('bg-danger');
                            $('td', row).addClass('text-white');
                        }
                    }
                },
            });
        }

        $('body').on('click', '.btn-approve-item', function() {
            var id = $(this).data('id');
            let url = "{{ route('update-manifest-status', ':id') }}";
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
                        success: function(response) {
                            if (response.status == 200) {
                                $('#modal-confirm').modal('hide');
                                showSuccess(response.msg);
                                table.ajax.reload();
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
