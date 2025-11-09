@extends('layout.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Master Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Master Product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <button class="btn btn-block btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-add-product"><i class="fa fa-plus"></i> Product</button>
                                </h3>
                            </div>
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Product</th>
                                            <th>Harga</th>
                                            <th>Satuan</th>
                                            <th>Diperbarui</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Add -->
    <div class="modal fade" id="modal-add-product">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-add-product">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Product</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama Product" required>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" name="price" placeholder="Harga Product" required>
                        </div>
                        <div class="form-group">
                            <label>Satuan</label>
                            <input type="text" class="form-control" name="unit" placeholder="Satuan (contoh: pcs, kg, dll)" required>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="modal-edit-product">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-product">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Product</label>
                            <input id="name" type="text" class="form-control" name="name" placeholder="Nama Product" required>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input id="price" type="number" class="form-control" name="price" placeholder="Harga Product" required>
                        </div>
                        <div class="form-group">
                            <label>Satuan</label>
                            <input id="unit" type="text" class="form-control" name="unit" placeholder="Satuan" required>
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

    <!-- Modal Delete -->
    <div class="modal fade" id="modal-delete-product">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-delete-product">
                    <div class="modal-body">
                       <p>Apakah anda yakin akan menghapus data product ini?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger btn-submit">Ya, Hapus</button>
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
                { data: 'name' },
                { data: 'price' },
                { data: 'unit' },
                { data: 'updated_at' },
                {
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return `
                            <button class="btn btn-primary btn-sm btn-edit" 
                                data-id="${data}" 
                                data-name="${row.name}" 
                                data-price="${row.price}" 
                                data-unit="${row.unit}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="${data}">
                                <i class="fa fa-trash"></i>
                            </button>`;
                    }
                },
            ],
        });
    }

    function refreshTable() {
        table.ajax.reload();
    }

    // CREATE
    $("#form-add-product").submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ route('master.products.store') }}",
            data: $(this).serialize(),
            success: function(response) {
                if (response.status == 200) {
                    $('#modal-add-product form')[0].reset();
                    $('#modal-add-product').modal('hide');
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

    // EDIT
    $('body').delegate('.btn-edit', 'click', function() {
        let id = $(this).data('id');
        $('#modal-edit-product').modal('show');
        $('#modal-edit-product #name').val($(this).data('name'));
        $('#modal-edit-product #price').val($(this).data('price'));
        $('#modal-edit-product #unit').val($(this).data('unit'));

        var url = "{{ route('master.products.update', ':id') }}";
        url = url.replace(':id', id);

        $("#form-edit-product").off('submit').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "PUT",
                url: url,
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == 200) {
                        $('#modal-edit-product form')[0].reset();
                        $('#modal-edit-product').modal('hide');
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
    });

    // DELETE
    $('body').delegate('.btn-delete', 'click', function() {
        let id = $(this).data('id');
        $('#modal-delete-product').modal('show');

        var url = "{{ route('master.products.destroy', ':id') }}";
        url = url.replace(':id', id);

        $("#form-delete-product").off('submit').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "DELETE",
                url: url,
                success: function(response) {
                    if (response.status == 200) {
                        $('#modal-delete-product').modal('hide');
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
    });
</script>
@endpush