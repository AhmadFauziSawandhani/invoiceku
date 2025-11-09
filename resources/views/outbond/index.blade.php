@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manifest Outbond</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Manifest Outbond</li>
                        </ol>
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
                                        <a class="btn btn-primary btn-sm" href="{{ route('outbond.create') }}"><i
                                                class="fa fa-plus"></i>Tambah Manifest Outbond</a>
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
                                            <th></th>
                                            <th>Tanggal</th>
                                            <th>Dibuat Oleh</th>
                                            <th>Supir COD</th>
                                            <th>Mengetahui</th>
                                            <th>Catatan</th>
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
    </div>

    <div class="modal fade" id="modalUpdateStatus" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <form id="form-update-status">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Update</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group date">
                                    <input type="date" class="form-control" name="tracking_date" id="tracking_date"
                                        required />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" name="note" id="note" required />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="PACKING">PACKING</option>
                                    <option value="PICKUP">PICKUP</option>
                                    <option value="DIKIRIM">DIKIRIM</option>
                                    <option value="SELESAI">SELESAI</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" id="additionalVendor" value="true">
                                    Ada Vendor Terusan
                                </label>
                            </div>
                        </div>
                        <div class="mb-3" id="vendorWrapper">
                            <div class="form-group">
                                <label>Nama Vendor:</label>
                                <div class="input-group date">
                                    <select class="form-control" name="vendor_id" id="vendor" style="width: 100%">
                                        <option value="">Pilih Vendor</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3" id="resiWrapper">
                            <div class="form-group">
                                <label>Resi Vendor</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" name="resi_vendor" id="resi_vendor" />
                                </div>
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
            $("#vendorWrapper,#resiWrapper").hide()
            $('#vendorFilter').select2({
                theme: "bootstrap",
                placeholder: "Pilih Vendor",
            });
            $('#vendor').select2({
                theme: "bootstrap",
                dropdownParent: $('#modalUpdateStatus'),
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
                        data: 'date_outbond',
                        name: 'date_outbond',
                    },
                    {
                        data: 'created_by.full_name',
                        name: 'created_by.full_name',
                    },
                    {
                        data: 'acknowledge',
                        name: 'acknowledge'
                    },
                    {
                        data: 'driver',
                        name: 'driver',
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let urlPrint = "{{ route('outbond.cetak', ':id') }}";
                            let urlUpdate = "{{ route('outbond.edit', ':id') }}";

                            urlPrint = urlPrint.replace(':id', data);
                            urlUpdate = urlUpdate.replace(':id', data);

                            return `<a href="${urlUpdate}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                    <a href="${urlPrint}" target="_blank" class="btn btn-warning"><i class="fas fa-print"></i></a>`
                        }
                    }

                ],
                // rowCallback: function(row, data, index) {
                //     if (data.shipping != null) {
                //         $('input[type="checkbox"]', row).prop('disabled', true);
                //         $('input[type="checkbox"]', row).prop('checked', false);
                //         $('input[type="checkbox"]', row).addClass('d-none');
                //         $('td', row).removeClass('dt-select');
                //         $(row).css('background-color', '#f0f0f0 !important');
                //     }
                // },

            });
        }

        function format(d) {
            // `d` is the original data object for the row
            if (d.manifest.length > 0) {
                let content = ''
                let total = 0
                let thead = `<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="13" style="background-color: yellow;">
                                    Data Manifest
                                </th>
                            </tr>
                            <tr>
                                <th>No</th>
                                <th>No STT</th>
                                <th>Vendor</th>
                                <th>Nama Pengirim</th>
                                <th>Tujuan</th>
                                <th>Koli</th>
                                <th>Kilo/Volume</th>
                                <th>Marketing</th>
                                <th>Tanggal Keluar Gudang</th>
                                <th>ETD-ETA</th>
                            </tr>
                        </thead>
                        <tbody> `;

                d.manifest.forEach((item, index) => {
                    content += `
                                <tr>
                                    <td class="text-center">${ index + 1 }</td>
                                    <td class="text-center">${ item.receipt_number }</td>
                                    <td class="text-center">${ item.vendor_name }</td>
                                    <td class="text-center">${ item.shipping_name }</td>
                                    <td class="text-center">${ item.destination }</td>
                                    <td class="text-center">${ item.total_colly }</td>
                                    <td class="text-center">${ item.total_actual } Kg / ${ item.total_volume }</td>
                                    <td class="text-center">${ item.sales_name }</td>
                                    <td class="text-center">${ item.warehouse_exit_date }</td>
                                    <td class="text-center">${ item.etd } - ${ item.eta }</td>
                                </tr>
                            `
                })
                return thead + content
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

        $("#additionalVendor").on('change', function() {
            if (this.checked) {
                $("#vendorWrapper,#resiWrapper").show()
            } else {
                $("#vendorWrapper,#resiWrapper").hide()
            }
        })

        function handleCreateButton() {
            if (selectedData.length > 0) {
                $('#btn-create-invoice').prop('disabled', false);
            } else {
                $('#btn-create-invoice').prop('disabled', true);
            }
        }

        $('#btn-create-invoice').on('click', function() {
            let url = "{{ route('shipping.create-invoice') }}?manifest_ids=" + selectedData.map(item => item.id)
                .join(',');
            if (selectedData.length > 0) {
                $("#modalUpdateStatus").modal('show')
            } else showError('Tidak ada manifest yang dipilih');
        });

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

        $('body').on('click', '.btn-update', function() {
            let id = $(this).data('id');
            selectedData.push({
                id: id
            })
            $("#modalUpdateStatus").modal('show')
        })

        $("#form-update-status").submit(function(e) {
            e.preventDefault();
            let btnText = $(".btn-submit-status").html();
            $(".btn-submit-status").prop('disabled', true);
            $(".btn-submit-status").html('<i class="fa fa-spinner fa-spin"></i> Loading...')
            $.ajax({
                type: "POST",
                url: "{{ route('tracking.update-selected-tracking') }}",
                data: {
                    manifest_id: selectedData.map(item => item.id),
                    status: $('#status').val(),
                    tracking_date: $('#tracking_date').val(),
                    note: $('#note').val(),
                    vendor_id: $('#vendor_id').val(),
                    resi_vendor: $('#resi_vendor').val(),
                },
                success: function(response) {
                    if (response.status == 200) {
                        $('#modalUpdateStatus form')[0].reset();
                        $('#modalUpdateStatus').modal('hide');
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
                },
                complete: function() {
                    $(".btn-submit-status").prop('disabled', false);
                    $(".btn-submit-status").html(btnText)
                }
            });
        })

        $("#modalUpdateStatus").on('hidden.bs.modal', function() {
            selectedData = []
            $("#form-update-status")[0].reset();
            $('#status').val('').trigger('change');
            refreshTable();
        })
    </script>
@endpush
