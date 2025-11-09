@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Laporan Keluhan Customer</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Data Laporan Keluhan Customer</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-lime">
                            <div class="inner">
                                <h3>{{ $keluhanHariIni }}</h3>

                                <p>Data Keluhan Hari ini
                                    {{ strtoupper((new \App\Helpers\GeneralHelpers())->convert_date_name_month(date('Y-m-d'))) . ' ' . date('Y') }}
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $keluhanPending }}</h3>

                                <p>Data Keluhan Belum Dilayani</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users-slash"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $keluhanProgress }}</h3>

                                <p>Data Keluhan Dilayani</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-user-check"></i>
                            </div>
                            <a href="#" class="small-box-footer"> </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $keluhanSelesai }}</h3>

                                <p>Data Keluhan Selesai Dilayani</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-user"></i>
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

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-header">
                                <!-- /.card-header -->
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
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Customer</th>
                                            <th>No Resi</th>
                                            <th>Email</th>
                                            <th>No Telepon</th>
                                            <th>Jenis Keluhan</th>
                                            <th>Deskripsi</th>
                                            <th>Status</th>
                                            <th style="width: 10%;">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>#123123</td>
                                            <td>Luthfy Wiratama</td>
                                            <td>example@email.com</td>
                                            <td>08212312321</td>
                                            <td>Order</td>
                                            <td>Baru</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm btn-edit-customer"
                                                    data-id="1">Lihat</button>
                                                <button class="btn btn-danger btn-sm btn-delete-customer"
                                                    data-id="1">Selesai</button>
                                            </td>
                                        </tr> --}}
                                    </tbody>
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

    <div class="modal fade" id="modalUpdateStatus" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <form id="form-update-status" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Update</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="On Progress">On Progress</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success btn-submit-status">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var table;
        $(document).ready(function() {
            initTable();
        });

        function formatPhoneNumber(phone) {
            if (phone.startsWith('0')) {
                return '+62' + phone.slice(1);
            }
            return phone; // Return the original phone number if it doesn't start with '0'
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
                destroy: true,
                ordering: false,
                ajax: {
                    url: '{!! url()->current() !!}',
                    data: function(d) {
                        d.vendor_id = $("#vendorFilter").val()
                        d.dateStart = $('.dateStart').val()
                        d.dateEnd = $('.dateEnd').val(),
                            d.invoice_type = $('#invoice_type').val()
                    },
                },
                columns: [{
                        data: 'full_name',
                    },
                    {
                        data: 'receipt_number'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'phone',
                    },
                    {
                        data: 'complaint_type'
                    },
                    {
                        data: 'complaint_description'
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'id',
                        render: function(data, type, row, meta) {
                            let linkWhatsapp = `https://wa.me/${formatPhoneNumber(row.phone)}`
                            return `<button class="btn btn-primary btn-update btn-sm" data-id="${data}" data-type="all" data-toggle="modal" data-target="#modalUpdateStatus"><i class="fas fa-check-circle"></i></button>
                            <a href="${linkWhatsapp}" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-phone"></i></a>`;
                        }
                    },

                ],
            });
        }

        function refreshTable() {
            table.ajax.reload();
        }

        $('body').on('click', '.btn-update', function() {
            let id = $(this).data('id');
            let url = "{{ route('update-complain', ':id') }}";
            url = url.replace(':id', id);
            $("#form-update-status").attr('action', url);
            $("#modalUpdateStatus").modal('show')
        })
    </script>
@endpush
