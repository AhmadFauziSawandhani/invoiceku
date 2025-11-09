@extends('layout.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Invoices<h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Invoice</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Invoice
                        </a>
                    </h3>
                </div>
                <div class="card-body">
                    <table id="invoice-table" class="table table-bordered table-striped w-100">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>No. Invoice</th>
                                <th>Customer</th>
                                <th>Tanggal Invoice</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@push('js')
<script>
$(document).ready(function () {
    $('#invoice-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'invoices/list-invoice',
            type: 'GET'
        },
        columns: [
            { data: 'invoice_number', name: 'invoice_number' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'invoice_date', name: 'invoice_date' },
            { data: 'status', name: 'status' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ]
    });

    // Tombol delete via AJAX
    $('#invoice-table').on('click', '.btn-delete', function() {
        let url = $(this).data('url');
        if(confirm('Yakin ingin menghapus invoice ini?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) {
                    $('#invoice-table').DataTable().ajax.reload();
                    alert('Invoice berhasil dihapus!');
                },
                error: function(err) {
                    alert('Gagal menghapus invoice!');
                }
            });
        }
    });
});
</script>
@endpush