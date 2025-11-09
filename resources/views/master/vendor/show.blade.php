@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Payment History Invoice Vendor {{ $vendor->name }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('master.vendors.index')}}">Vendor</a></li>
                            <li class="breadcrumb-item active">Payment History</li>
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
                            <form id="form-filter">
                                <div class="card-footer d-flex ">
                                    <button type="button" class="btn btn-sm btn-light border text-success" id="btnExport">
                                        <i class="fa fa-fw fa-file-excel"></i> Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th>No</th> --}}
                                            <th>Nomor Invoice</th>
                                            <th>Tipe</th>
                                            <th>Tanggal</th>
                                            <th>Jatuh Tempo</th>
                                            <th class="text-center">Invoice</th>
                                            <th class="text-center">Payment</th>
                                            <th class="text-center">Saldo</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-center">Total</th>
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
            table = $('#list-manage').DataTable({
                language: {
                    "paginate": {
                        'previous': 'Prev',
                        'next': 'Next'
                    }
                },
                searching: true,
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: '{!! url()->current() !!}',
                    data: function(d) {
                        d.dateStart = $('.dateStart').val()
                        d.dateEnd = $('.dateEnd').val()
                    },
                    dataSrc: function(resp) {
                        resp.data.forEach(function(item, index) {
                            if (index == 0) {
                                item.saldo = parseInt(item.amount)
                            } else {
                                item.saldo = item.type == 'payment' ? parseInt(resp.data[index - 1].saldo) - parseInt(item.amount) : parseInt(resp.data[index - 1].saldo) + parseInt(item.amount)
                            }
                            if (item.type == 'payment') {
                                item.payment_amount = parseInt(item.amount)
                                item.invoice_amount = 0
                            } else {
                                item.payment_amount = 0
                                item.invoice_amount = parseInt(item.amount)
                            }
                        })
                        return resp.data
                    }
                },
                columns: [{
                        data: 'invoice_no',
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
                        data: 'invoice_amount',
                        render: function(data, type, row, meta) {
                            return data > 0 ? formatRupiah(data) : '-'
                        }
                    },
                    {
                        data: 'payment_amount',
                        render: function(data, type, row, meta) {
                            return data > 0 ? formatRupiah(data) : '-'
                        }
                    },
                    {
                        data: 'saldo',
                        render: function(data, type, row, meta) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        data: 'remark'
                    },
                ],
                createdRow: function ( row, data, index ) {
                    if ( moment().isAfter(moment(data.due_date)) ) {
                        $('td', row).addClass('bg-danger');
                    }
                },
                headerCallback: function(thead, data, start, end, display) {
                    var json = table.ajax.json();
                    // Remove the formatting to get integer data for summation
                    let intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i :
                            0;
                    };
                    $(thead).find('th').eq(4).html('IDR INVOICE <br>' +
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalInvoice)
                    )
                    $(thead).find('th').eq(5).html('IDR PAYMENT <br>' +
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalPayment)
                    )
                    $(thead).find('th').eq(6).html('IDR SALDO <br>' +
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalSaldo ??
                            0)
                    )
                    // Update footer

                },
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
                    // Update footer
                    $(api.column(0).footer()).html('TOTAL');
                    $(api.column(4).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalInvoice)
                    )
                    $(api.column(5).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalPayment)
                    )
                    $(api.column(6).footer()).html(
                        $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.totalSaldo ??
                            0)
                    )
                },

            });

            $('#vendorimport').select2({
                theme: "bootstrap",
                placeholder: "Pilih Vendor",
                dropdownParent: $('#modelId')
            });

            $("#form-filter").submit(function(e) {
                e.preventDefault();
                refreshTable();
            })
        });

        function refreshTable() {
            table.ajax.reload();
            if ($('#vendor').val() == '') {
                $("#btnExport").attr("disabled", true);
            }else{
                $("#btnExport").attr("disabled", false);
            }
        }

        function resetFilter() {
            $('#form-filter')[0].reset();
            $('#vendor').val('').trigger('change');
            refreshTable();
        }

        function exportToExcel() {
            $.ajax({
                type: "post",
                url: "{{ route('payment-history-export') }}",
                data: {
                    vendor_id: "{{ $vendor->id }}",
                    dateStart: $('.dateStart').val(),
                    dateEnd: $('.dateEnd').val(),
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    const url = window.URL.createObjectURL(new Blob([response]))
                    const link = document.createElement('a')
                    const vendor = '{{ $vendor->name }}'
                    link.href = url
                    link.setAttribute('download', `Data Laporan Payment History ${vendor}.xlsx`)
                    document.body.appendChild(link)
                    link.click()
                }
            });
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
                    var err = JSON.parse(xhr.responseText);
                    showError(err.messages);
                }
            });
        });

        $(".btn-reset").click(function() {
            resetFilter();
        });
        $("#btnExport").click(function() {
            exportToExcel();
        });

        $('#vendor').change(function() {
            if ($(this).val() == '') $("#btnExport").attr("disabled", true);
        });

        function formatRupiah(data) {
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(parseInt(data) || 0)
            return formatted
        }
    </script>
@endpush
