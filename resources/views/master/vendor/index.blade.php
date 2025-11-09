@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Master Vendor</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Master Vendor</li>
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
                                <h3 class="card-title">
                                    <button class="btn btn-block btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-add-vendor"><i class="fa fa-plus"></i>Tambah Vendor</button>
                                </h3>
                            </div>
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th>No</th> --}}
                                            <th>Nama Vendor</th>
                                            <th>Nomor Telepon</th>
                                            <th>Nama Bank</th>
                                            <th>Atas Nama Bank</th>
                                            <th>No Rekening</th>
                                            <th>Invoice</th>
                                            <th>Payment</th>
                                            <th>Saldo</th>
                                            <th style="width: 10%;">#</th>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Vendor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-add-vendor">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nama Vendor</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama Vendor"
                                style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Telepon</label>
                            <input type="text" class="form-control" name="phone" placeholder="Telepon"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nama Bank</label>
                            <input type="text" class="form-control" name="account_bank" placeholder="Nama Bank"
                                style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Atas Nama Bank</label>
                            <input type="text" class="form-control" name="account_name" placeholder="Atas Nama Bank"
                                style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">No Rekening Bank</label>
                            <input type="text" class="form-control" name="account_number" placeholder="No Rekening Bank"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
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
    <div class="modal fade" id="modal-add-account">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Rekening</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-add-account">
                    <div class="modal-body">
                        <input type="hidden" name="vendor_id" id="vendor_id">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nama Bank</label>
                            <input type="text" class="form-control" name="account_bank" placeholder="Nama Bank"
                                style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Atas Nama Bank</label>
                            <input type="text" class="form-control" name="account_name" placeholder="Atas Nama Bank"
                                style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">No Rekening Bank</label>
                            <input type="text" class="form-control" name="account_number"
                                placeholder="No Rekening Bank"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
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
    <div class="modal fade" id="modal-list-account">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Daftar Rekening</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <table class="table table-bordered">
                       <thead>
                           <tr>
                               <th>Nama Bank</th>
                               <th>Atas Nama Bank</th>
                               <th>No Rekening</th>
                       </thead>
                       <tbody id="list-account"></tbody>
                   </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
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
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nama Vendor</label>
                            <input id="name" type="text" class="form-control" name="name"
                                placeholder="Nama Vendor" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Telepon</label>
                            <input id="phone" type="text" class="form-control" name="phone"
                                placeholder="Telepon"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nama Bank</label>
                            <input id="account_bank" type="text" class="form-control" name="account_bank"
                                placeholder="Nama Bank" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Atas Nama Bank</label>
                            <input id="account_name" type="text" class="form-control" name="account_name"
                                placeholder="Atas Nama Bank" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">No Rekening Bank</label>
                            <input id="account_number" type="text" class="form-control" name="account_number"
                                placeholder="No Rekening Bank"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
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
@endsection
@push('js')
    <script>
        var table;
        $(document).ready(function() {
            initTable();
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
                    url: '{!! url()->current() !!}'
                },
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'account_bank'
                    },
                    {
                        data: 'account_name'
                    },
                    {
                        data: 'account_number'
                    },
                    {
                        data: 'total_invoice',
                        render: function(data, type, row, meta) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        data: 'total_payment',
                        render: function(data, type, row, meta) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        data: 'saldo',
                        render: function(data, type, row, meta) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row, meta) {
                            let urlShow = "{{ route('master.vendors.show', ':id') }}";
                            urlShow = urlShow.replace(':id', data);
                            let urlInvoices = "{{ route('master.list-invoices-vendor', ':id') }}";
                            urlInvoices = urlInvoices.replace(':id', data);
                            return `<button class="btn btn-primary btn-approve-item btn-sm btn-edit" data-id="${data}" data-name="${row.name}" data-phone="${row.phone}" data-account_bank="${row.account_bank}" data-account_name="${row.account_name}" data-account_number="${row.account_number}"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-primary btn-approve-item btn-sm btn-add-account" data-id="${data}"><i class="fa fa-plus"></i></button>
                            <button class="btn btn-primary btn-approve-item btn-sm btn-account-list" data-accounts='${JSON.stringify(row.accounts)}'><i class="fa fa-list"></i></button>
                            <a href="${urlShow}" class="btn btn-primary btn-approve-item btn-sm"><i class="fa fa-eye"></i></a>
                            <a href="${urlInvoices}" class="btn btn-primary btn-approve-item btn-sm"><i class="fa fa-file-invoice"></i></a>`
                        }
                    },

                ],
            });
        }

        function refreshTable() {
            table.ajax.reload();
        }

        $("#form-add-vendor").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('master.vendors.store') }}",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == 200) {
                        $('#modal-add-vendor form')[0].reset();
                        $('#modal-add-vendor').modal('hide');
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
        });

        $('body').delegate('.btn-edit', 'click', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let phone = $(this).data('phone');
            let account_bank = $(this).data('account_bank');
            let account_name = $(this).data('account_name');
            let account_number = $(this).data('account_number');
            $('#modal-edit-vendor').modal('show');
            $('#modal-edit-vendor #name').val(name);
            $('#modal-edit-vendor #phone').val(phone);
            $('#modal-edit-vendor #account_bank').val(account_bank);
            $('#modal-edit-vendor #account_name').val(account_name);
            $('#modal-edit-vendor #account_number').val(account_number);
            var url = "{{ route('master.vendors.update', ':id') }}";
            url = url.replace(':id', id);
            $("#form-edit-vendor").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "PUT",
                    url: url,
                    data: $(this).serialize(),
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
                        var err = eval("(" + xhr.responseText + ")");
                        showError(err.message);
                    }
                });
            })
        });
        $('body').delegate('.btn-add-account', 'click', function() {
            let id = $(this).data('id');
            $('#modal-add-account').modal('show');
            $('#modal-add-account #vendor_id').val(id);
            var url = "{{ route('account.store') }}";
            url = url.replace(':id', id);
            $("#form-add-account").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 200) {
                            $('#modal-add-account form')[0].reset();
                            $('#modal-add-account').modal('hide');
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
        });
        $('body').delegate('.btn-account-list', 'click', function() {
            let accounts = $(this).data('accounts');
            $('#modal-list-account #list-account').empty();
            $('#modal-list-account').modal('show');
            if(accounts.length >0){
                accounts.forEach(account => {
                    $('#modal-list-account #list-account').append(`<tr>
                        <td>${account.account_bank}</td>
                        <td>${account.account_name}</td>
                        <td>${account.account_number}</td>
                        </tr>`)
                })
            }else{
                $('#modal-list-account #list-account').append(`<tr>
                    <td colspan="3">Data Rekening Belum Ada</td>
                </tr>`)
            }
        })

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
