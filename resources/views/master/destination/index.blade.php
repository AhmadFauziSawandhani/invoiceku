@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Master Tujuan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Master Tujuan</li>
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
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th>No</th> --}}
                                            <th>Nama Tujuan</th>
                                            <th>Code</th>
                                            <th>Harga</th>
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

    

    <div class="modal fade" id="modal-edit-destination">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Tujuan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-destination">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Tujuan</label>
                            <input id="name" type="text" class="form-control" name="name"
                                placeholder="Nama Tujuan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kode">Kode</label>
                            <input id="code" type="text" class="form-control" name="code"
                                placeholder="Kode" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input id="price" type="text" class="form-control" name="price"
                                placeholder="Harga">
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
                columns: [
                    {
                        data: 'name',
                        sortable: false,
                        render: function(data, type, row, meta) {
                        return `${row.regency.name} - ${data}`
                        }
                    },
                    {
                        data: 'code',
                        sortable: false

                    },
                    {
                        data: 'price',
                        sortable: false

                    },
                    {
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return `<button class="btn btn-primary btn-approve-item btn-sm btn-edit" data-id="${data}" data-name="${row.name}" data-code="${row.code}" data-price="${row.price}"><i class="fa fa-edit"></i></button>
                            `
                        },
                        sortable: false
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
            let code = $(this).data('code');
            let price = $(this).data('price');
            $('#modal-edit-destination').modal('show');
            $('#modal-edit-destination #name').val(name);
            $('#modal-edit-destination #code').val(code);
            $('#modal-edit-destination #price').val(price);
            var url = "{{ route('master.destination.update', ':id') }}";
            url = url.replace(':id', id);
            $("#form-edit-destination").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "PUT",
                    url: url,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 200) {
                            $('#modal-edit-destination form')[0].reset();
                            $('#modal-edit-destination').modal('hide');
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
    </script>
@endpush
