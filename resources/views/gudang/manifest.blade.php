@extends('layout.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">TANGGAL {{ (new \App\Helpers\GeneralHelpers())->date_format_id(date('Y-m-d')) }}
                        </h1>
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
                            <div class="card-header">
                                <h3 class="card-title">
                                    @if (in_array(Auth()->user()->role, ['admin', 'gudang']))
                                        <a class="btn btn-block btn-primary btn-sm"
                                            href="{{ route('manifest-barang.create') }}"><i class="fa fa-plus"></i>Tambah
                                            Manifest</a>
                                    @endif
                                </h3>
                            </div>
                            <div class="card-body">
                                @if (in_array(auth()->user()->role, ['admin', 'finance']))
                                    <div class="d-flex py-4">
                                        <button id="btn-create-invoice" disabled class="btn btn-danger btn-sm"><i
                                                class="fa fa-file-invoice"></i>Buat Invoice</button>
                                    </div>
                                @endif
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>No STT</th>
                                            <th>Tanggal</th>
                                            <th>Marketing</th>
                                            <th>Tujuan</th>
                                            <th>Nama Pengirim</th>
                                            <th>Asal</th>
                                            <th>Koli</th>
                                            <th>Kilo/Volume</th>
                                            <th>Total Hari Mengendap</th>
                                            <th>Foto Barang</th>
                                            <th>Foto Surat Jalan</th>
                                            <th>Nama Vendor</th>
                                            <th>#</th>
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
        <div class="modal fade" id="modal-confirm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Status</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin mengubah status manifest ini?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-confirm" class="btn btn-primary">Ya, Ubah</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-delete-manifest">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Manifest</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus manifest ini?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-confirm-delete" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
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
                refreshTable()
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
                    url: '{!! url()->current() !!}',
                    data: function(d) {
                        d.start_date = $('.start_date').val(),
                            d.end_date = $('.end_date').val(),
                            d.status = $('#statusGudang').val()
                    }
                },
                columnDefs: [{
                    orderable: false,
                    render: DataTable.render.select(),
                    targets: 0
                }],
                fixedColumns: {
                    start: 2
                },
                select: {
                    style: 'multi',
                    selector: 'td:first-child',
                    headerCheckbox: false
                },
                columns: [{
                        data: 'id',
                    },
                    {
                        data: 'receipt_number',
                        name: 'receipt_number'
                    },
                    {
                        data: 'date_manifest',
                        name: 'date_manifest',
                        render: $.fn.dataTable.render.moment('DD-MM-YYYY'),
                    },
                    {
                        data: 'sales_name',
                        name: 'sales_name',
                    },
                    {
                        data: 'destination',
                        name: 'destination'
                    },
                    {
                        data: 'shipping_name',
                        name: 'shipping_name',
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
                        data: 'total_chargeable_weight',
                        name: 'total_chargeable_weight',
                        render: function(data, type, row) {
                            return `${data} kg / ${row.total_volume}`
                        }
                    },
                    {
                        data: 'date_manifest',
                        name: 'date_manifest',
                        render: function(data, type, row) {
                            if (row.warehouse_exit_date) return 'Sudah Berangkat'
                            let exitDate = row.warehouse_exit_date ? moment(row.warehouse_exit_date) :
                                null;
                            let totalDay = moment(exitDate ?? moment()).diff(moment(data), 'days');
                            let fromNow = moment(data).fromNow().replace('days', 'hari').replace(
                                    'hours', 'jam').replace('minutes', 'menit').replace('seconds',
                                    'detik').replace('a day', '1 hari').replace('a month', '1 bulan')
                                .replace('a year', '1 tahun').replace('a year', '1 tahun').replace(
                                    'a week', '1 minggu').replace('a week', '1 minggu').replace('ago',
                                    '');
                            return exitDate ? totalDay + ' Hari' : fromNow;
                        }
                    },
                    {
                        data: 'photo_product',
                        name: 'photo_product',
                        render: function(data, type, row) {
                            return `<a href="{{ asset('storage/${data}') }}" target="_blank"><img src="{{ asset('storage/${data}') }}" width="100px"></a>`
                        }
                    },
                    {
                        data: 'photo_travel_document',
                        name: 'photo_travel_document',
                        render: function(data, type, row) {
                            if (data)
                                return `<a href="{{ asset('storage/${data}') }}" target="_blank"><img src="{{ asset('storage/${data}') }}" width="100px"></a>`
                            else return '-'
                        }
                    },
                    {
                        data: 'vendor_name',
                        render: function(data,type,row,meta){
                            return data ? data : row.vendor_typ ?? '-';
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('edit-manifest', ':id') }}";
                            let showUrl = "{{ route('show-manifest', ':id') }}";
                            let copyUrl = "{{ route('duplicate-manifest', ':id') }}";
                            let resiUrl = "{{ route('cetakResi', ':id') }}";
                            showUrl = showUrl.replace(':id', data);
                            editUrl = editUrl.replace(':id', data);
                            resiUrl = resiUrl.replace(':id', data);
                            copyUrl = copyUrl.replace(':id', data);
                            if (row.status == 0) {
                                @if (in_array(auth()->user()->role, ['admin', 'gudang']))
                                    return `<a href="${editUrl}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="${copyUrl}" class="btn btn-info btn-sm"><i class="fa fa-copy"></i></a>
                                <button data-id="${data}" class="btn btn-danger btn-delete btn-sm"><i class="fa fa-trash"></i></button>
                                <button data-id="${data}"  class="btn btn-success btn-approve-item btn-sm"><i class="fa fa-check"></i></button>`
                                @else
                                    return `<a href="${showUrl}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                            <a href="${resiUrl}" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>`
                                @endif
                            } else {
                                @if (in_array(auth()->user()->role, ['admin', 'gudang']))
                                    if (row.shipping == null) {
                                        let btn =
                                            `<a href="${editUrl}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                            <a href="${copyUrl}" class="btn btn-info btn-sm"><i class="fa fa-copy"></i></a>
                                            <a href="${showUrl}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                            <a href="${resiUrl}" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>`
                                        return btn
                                    } else {
                                        let btn =
                                            `<a href="${editUrl}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                            <a href="${copyUrl}" class="btn btn-info btn-sm"><i class="fa fa-copy"></i></a>
                                            <a href="${showUrl}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                                    <a href="${resiUrl}" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>`
                                        return btn
                                    }
                                @else
                                    return `<a href="${showUrl}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                            <a href="${resiUrl}" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>`
                                @endif
                            }
                        }
                    }

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
                rowCallback: function(row, data, index) {
                    if (data.shipping != null) {
                        $('input[type="checkbox"]', row).prop('disabled', true);
                        $('input[type="checkbox"]', row).prop('checked', false);
                        $('input[type="checkbox"]', row).addClass('d-none');
                        $('td', row).removeClass('dt-select');
                        $(row).css('background-color', '#f0f0f0 !important');
                    }
                },

            });
        }

        let selectedData = [];
        $('#list-manage').on('select.dt', function(e, dt, type, indexes) {
            let data = dt.rows(indexes).data().toArray();
            selectedData = [...selectedData, ...data];
            handleCreateButton();
        });

        $('#list-manage').on('deselect.dt', function(e, dt, type, indexes) {
            let data = dt.rows(indexes).data().toArray();
            selectedData = selectedData.filter(row => !data.includes(row));
            handleCreateButton();
        });

        function handleCreateButton() {
            if (selectedData.length > 0) {
                $('#btn-create-invoice').prop('disabled', false);
            } else {
                $('#btn-create-invoice').prop('disabled', true);
            }
        }

        function resetFilter() {
            $('#form-filter')[0].reset();
            $('#statusGudang').val('').trigger('change');
            refreshTable();
        }

        function refreshTable() {
            table.ajax.reload();
        }

        $('#btn-create-invoice').on('click', function() {
            let url = "{{ route('shipping.create-invoice') }}?manifest_ids=" + selectedData.map(item => item.id)
                .join(',');
            if (selectedData.length > 0) window.location.href = url;
            else showError('Tidak ada manifest yang dipilih');
        });

        $('body').on('click', '.btn-approve-item', function() {
            var id = $(this).data('id');
            let url = "{{ route('update-manifest-status', ':id') }}";
            let urlResi = "{{ route('cetakResi', ':id') }}";
            url = url.replace(':id', id);
            urlResi = urlResi.replace(':id', id);
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
                                window.open(
                                    urlResi,
                                    '_blank' // <- This is what makes it open in a new window.
                                );
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

        $('body').on('click', '.btn-delete', function() {
            var id = $(this).data('id');
            let url = "{{ route('delete-manifest', ':id') }}";
            url = url.replace(':id', id);
            $('#modal-delete-manifest').modal('show');
            $("#btn-confirm-delete").click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: url,
                    success: function(response) {
                        if (response.status == 200) {
                            $('#modal-delete-manifest').modal('hide');
                            showSuccess(response.msg);
                            table.ajax.reload();
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
        });
    </script>
@endpush
