@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Master Customer</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Master Customer</li>
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
                            <div class="card-header">
                                <h3 class="card-title">
                                    <button class="btn btn-block btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-add-customers"><i class="fa fa-plus"></i> Customer</button>
                                </h3>
                            </div>
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th>No</th> --}}
                                            <th>Nama Customer</th>
                                            <th>Nomor Telepon</th>
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

    <!-- Modal Add -->
    <div class="modal fade" id="modal-add-customers">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Customer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-add-customers">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Customer</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama Customer" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="number" class="form-control" name="phone" placeholder="Phone Number" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-customer">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Customer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-customer">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nama Customer</label>
                            <input id="name" type="text" class="form-control" name="name"
                                placeholder="Nama Vendor" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Phone Number</label>
                            <input id="phone" type="text" class="form-control" name="phone" placeholder="Telepon"
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
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return `<button class="btn btn-primary btn-approve-item btn-sm btn-edit" data-id="${data}" data-name="${row.name}" data-phone="${row.phone}"><i class="fa fa-edit"></i></button>`
                        }
                    },

                ],
            });
        }

        function refreshTable() {
            table.ajax.reload();
        }

        $('body').delegate('.btn-edit', 'click', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let email = $(this).data('email');
            let phone = $(this).data('phone');
            
            $('#modal-edit-customer').modal('show');
            $('#name').val(name);
            $('#email').val(email);
            $('#phone').val(phone);

            var url = "{{ route('master.customers.update', ':id') }}";
            url = url.replace(':id', id);
            $("#form-edit-customer").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "PUT",
                    url: url,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 200) {
                            $('#modal-edit-customer form')[0].reset();
                            $('#modal-edit-customer').modal('hide');
                            showSuccess(response.msg);
                            refreshTable();
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

        // CREATE
        $("#form-add-customers").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('master.customers.store') }}",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == 200) {
                        $('#modal-add-customers form')[0].reset();
                        $('#modal-add-customers').modal('hide');
                        showSuccess(response.msg);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showError(response.msg);
                    }
                },
                error: function(xhr) {
                    showError(xhr.responseJSON.message);
                }
            });
        });
    </script>
@endpush
