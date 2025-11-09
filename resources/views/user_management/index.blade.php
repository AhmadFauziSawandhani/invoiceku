@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Management User</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Management User</li>
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
                                        data-target="#modal-add-user"><i class="fa fa-plus"></i>Tambah User</button>
                                </h3>
                            </div>
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th>No</th> --}}
                                            <th>Nama User</th>
                                            <th>Email</th>
                                            <th>Role</th>
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

    <div class="modal fade" id="modal-add-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-add-user">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nama User</label>
                            <input id="name" type="text" class="form-control" name="full_name"
                                placeholder="Nama User" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Email</label>
                            <input id="email" type="email" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Role</label>
                            <select class="form-control" name="role" id="role">
                                <option value="" selected>Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="finance">Keuangan</option>
                                <option value="gudang">Gudang</option>
                                <option value="tracking">Tracking</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input id="password" type="password" class="form-control" name="password"
                                placeholder="Password">
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
    <div class="modal fade" id="modal-edit-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-user">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nama User</label>
                            <input id="name" type="text" class="form-control" name="full_name"
                                placeholder="Nama User" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Email</label>
                            <input id="email" type="email" class="form-control" name="email"
                                placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Role</label>
                            <select class="form-control" name="role" id="role">
                                <option value="" selected>Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="finance">Keuangan</option>
                                <option value="gudang">Gudang</option>
                                <option value="tracking">Tracking</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input id="password" type="password" class="form-control" name="password"
                                placeholder="Password">
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
                        data: 'full_name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return `<button class="btn btn-primary btn-approve-item btn-sm btn-edit" data-id="${data}" data-name="${row.full_name}" data-email="${row.email}" data-role="${row.role}"><i class="fa fa-edit"></i></button>`
                        }
                    },

                ],
            });
        }

        function refreshTable() {
            table.ajax.reload();
        }

        $("#form-add-user").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('user-management.store') }}",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == 200) {
                        $('#modal-add-user form')[0].reset();
                        $('#modal-add-user').modal('hide');
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
            let email = $(this).data('email');
            let role = $(this).data('role');

            $('#modal-edit-user').modal('show');
            $('#modal-edit-user #name').val(name);
            $('#modal-edit-user #email').val(email);
            $('#modal-edit-user #email').val(email);
            $('#modal-edit-user #role').val(role).trigger('change');

            var url = "{{ route('user-management.update', ':id') }}";
            url = url.replace(':id', id);
            $("#form-edit-user").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "PUT",
                    url: url,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 200) {
                            $('#modal-edit-user form')[0].reset();
                            $('#modal-edit-user').modal('hide');
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

        $('#modal-edit-user').on('hidden.bs.modal', function() {
            $('#modal-edit-user form')[0].reset();
        })
        $('#modal-add-user').on('hidden.bs.modal', function() {
            $('#modal-add-user form')[0].reset();
        })
    </script>
@endpush
