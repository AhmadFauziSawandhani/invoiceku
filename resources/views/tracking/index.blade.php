@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Tracking</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Data Tracking</li>
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
                                                    <label>Status</label>
                                                    <select name="status" class="form-control" id="statusResi">
                                                        <option value="">Pilih Status</option>
                                                        @foreach ($statuses as $status)
                                                            <option value="{{ $status->name }}"
                                                                data-flag="{{ $status->form_condition }}">
                                                                {{ $status->name }}</option>
                                                        @endforeach
                                                    </select>
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
                                        <a class="btn btn-primary btn-sm" href="{{ route('tracking.create') }}"><i
                                                class="fa fa-plus"></i>Tambah Data Tracking</a>
                                        {{-- <button class="btn btn-success btn-md mx-1" data-toggle="modal"
                                        data-target="#modelId"><i class="fa fa-upload"></i>
                                        &nbsp;&nbsp;Import&nbsp; </button> --}}
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                @if (in_array(auth()->user()->role, ['admin', 'tracking']))
                                    <div class="d-flex py-4">
                                        <button id="btn-create-invoice" disabled class="btn btn-primary btn-sm"><i
                                                class="fas fa-check-circle"></i>Update Status</button>
                                    </div>
                                @endif
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>No STT</th>
                                            <th>Vendor</th>
                                            <th>Nama Pengirim</th>
                                            <th>Tujuan</th>
                                            <th>Koli</th>
                                            <th>Kilo/Volume</th>
                                            <th>Marketing</th>
                                            <th>Tanggal Keluar Gudang</th>
                                            <th>ETD-ETA</th>
                                            <th>Status/Note</th>
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
                                <label>Status</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="">Pilih Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->name }}" data-flag="{{ $status->form_condition }}">
                                            {{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control" name="note" id="note" required></textarea>
                            </div>
                        </div>
                        <div class="mb-3" id="courirWrapper">
                            <div class="form-group">
                                <label>Nama Kurir</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" name="courier_name" id="courier_name" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3" id="phoneWrapper">
                            <div class="form-group">
                                <label>Whatsapp Kurir</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" name="courier_phone"
                                        id="courier_phone" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3" id="proofWrapper">
                            <div class="form-group">
                                <label>Bukti Kirim</label>
                                <div class="input-group date">
                                    <input type="file" class="form-control" name="proof" id="proof" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" id="additionalVendor"
                                        value="true">
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
            $("#vendorWrapper,#resiWrapper,#courirWrapper,#phoneWrapper,#proofWrapper").hide()
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
                            d.invoice_type = $('#invoice_type').val(),
                            d.status = $('#statusResi').val()
                    },
                },
                columnDefs: [{
                    orderable: false,
                    render: DataTable.render.select(),
                    targets: 1
                }],
                fixedColumns: {
                    start: 3
                },
                select: {
                    style: 'multi',
                    selector: 'td:nth-child(2)',
                    headerCheckbox: false
                },
                columns: [{
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        data: 'id',
                    },
                    {
                        data: 'receipt_number',
                        name: 'receipt_number',
                    },
                    {
                        data: 'last_tracking.vendor_name',
                        name: 'last_tracking.vendor_name',
                        render: function(data,type,row,meta){
                            if(data) return data
                            else return row.vendor_name ? row.vendor_name : row.vendor_type ?? '-'
                        }
                    },
                    {
                        data: 'shipping_name',
                        name: 'shipping_name'
                    },
                    {
                        data: 'destination',
                        name: 'destination',
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
                        data: 'sales_name',
                        name: 'sales_name'
                    },
                    {
                        data: 'warehouse_exit_date',
                        name: 'warehouse_exit_date'
                    },
                    {
                        data: 'etd',
                        name: 'etd',
                        render: function(data, type, row) {
                            return moment(data).format('DD-MM-YYYY') + ' - ' + moment(row.eta).format(
                                'DD-MM-YYYY');
                        }
                    },
                    {
                        data: 'last_tracking.status',
                        name: 'last_tracking.status',
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button class="btn btn-primary btn-update" data-id="${data}" data-type="all"><i class="fas fa-check-circle"></i></button>`
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
            if (d.product.length > 0) {
                let content = ''
                let total = 0
                let thead = `<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="13" style="background-color: yellow;">
                                    PACKING LIST
                                </th>
                            </tr>
                            <tr>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">#</th>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">Nama
                                    Barang</th>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">
                                    Koli (Colly)</th>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">
                                    Kilo</th>
                                <th scope="col" colspan="3" style="background-color:#92d14f;" class="text-center">Dimensi</th>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">
                                    Volume</th>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">
                                    Volume M3</th>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">
                                    Actual</th>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">
                                    Chargeable Weight
                                </th>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">
                                    Packaging</th>
                                <th scope="col" rowspan="2" style="vertical-align: middle;background-color:#92d14f;" class="text-center">Charge
                                    Packaging</th>
                            </tr>
                            <tr>
                                <th scope="col" style="background-color:#92d14f;" class="text-center">P</th>
                                <th scope="col" style="background-color:#92d14f;" class="text-center">L</th>
                                <th scope="col" style="background-color:#92d14f;" class="text-center">T</th>
                            </tr>
                        </thead>
                        <tbody> `;

                d.product.forEach((item, index) => {
                    content += `
                                <tr>
                                    <td class="text-center">${ index + 1 }</td>
                                    <td class="text-center">${ item.product_name }</td>
                                    <td class="text-center">${ item.colly }</td>
                                    <td class="text-center">${ item.weight }</td>
                                    <td class="text-center">${ item.dimension_p }</td>
                                    <td class="text-center">${ item.dimension_l }</td>
                                    <td class="text-center">${ item.dimension_t }</td>
                                    <td class="text-center">${ item.volume }</td>
                                    <td class="text-center">${ item.volume_m3 }</td>
                                    <td class="text-center">${ item.actual }</td>
                                    <td class="text-center">${ item.chargeable_weight }</td>
                                    <td class="text-center">${ item.packaging }</td>
                                    <td class="text-center">${ item.charge_packaging }</td>
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
        $("#status").on('change', function() {
            let data = $(this).find(':selected').data('flag')
            console.log(data);

            if (data == 'wa_courir') {
                $("#courirWrapper").show()
                $("#phoneWrapper").show()
                $("#proofWrapper").hide()
            } else if (data == 'photo') {
                $("#proofWrapper").show()
                $("#courirWrapper").hide()
                $("#phoneWrapper").hide()
            } else {
                $("#proofWrapper").hide()
                $("#courirWrapper").hide()
                $("#phoneWrapper").hide()
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
            const formData = new FormData()
            selectedData.forEach((item, index) => {
                formData.append('manifest_id[' + index + ']', item.id)
            })
            formData.append('status', $('#status').val())
            formData.append('tracking_date', $('#tracking_date').val())
            formData.append('note', $('#note').val())
            formData.append('vendor_id', $('#vendor_id').val())
            formData.append('resi_vendor', $('#resi_vendor').val())
            formData.append('courier_name', $('#courier_name').val())
            formData.append('courier_phone', $('#courier_phone').val())
            formData.append('proof', $('#proof')[0].files[0])

            $.ajax({
                type: "POST",
                url: "{{ route('tracking.update-selected-tracking') }}",
                data: formData,
                processData: false,
                contentType: false,
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
