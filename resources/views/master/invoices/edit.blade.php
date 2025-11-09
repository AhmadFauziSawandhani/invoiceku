@extends('layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between">
            <h1>Edit Invoice #{{ $invoice->invoice_number }}</h1>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </section>

    <section>
        <form id="invoiceForm" action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                {{-- Info dasar invoice --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>No Invoice</label>
                        <input type="text" name="invoice_number" class="form-control"
                            value="{{ $invoice->invoice_number }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Tanggal Invoice</label>
                        <input type="date" name="invoice_date" class="form-control"
                            value="{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label>Customer</label>
                        <select name="customer_id" class="form-control select">
                            @foreach ($customers as $cust)
                                <option value="{{ $cust->id }}" {{ $cust->id == $invoice->customer_id ? 'selected' : '' }}>
                                    {{ $cust->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="PAID" {{ $invoice->status == 'PAID' ? 'selected' : '' }}>PAID</option>
                            <option value="PENDING" {{ $invoice->status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                        </select>
                    </div>
                </div>

                <hr>

                {{-- Produk --}}
                <h6>Produk</h6>
                <table class="table table-bordered" id="productTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga Beli / unit</th>
                            <th>Harga Jual / unit</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $index => $item)
                        <tr>
                            <td>
                                <select name="products[{{ $index }}][product_id]" class="form-control select-product">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($products as $prod)
                                        <option value="{{ $prod->id }}"
                                            {{ $prod->id == $item->product_id ? 'selected' : '' }}>
                                            {{ $prod->name }} (Rp {{ number_format($prod->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="products[{{ $index }}][buying_price]" class="form-control buying-price"
                                    value="{{ $item->buying_price }}"></td>
                            <td><input type="number" name="products[{{ $index }}][price]" class="form-control selling-price"
                                    value="{{ $item->selling_price }}"></td>
                            <td><input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity"
                                    value="{{ $item->quantity }}"></td>
                            <td><input type="number" name="products[{{ $index }}][subtotal]" class="form-control subtotal"
                                    value="{{ $item->subtotal }}" readonly></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm removeRow">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5"></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm" id="addRow">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                {{-- Total --}}
                <div class="text-right">
                    <h5>Total: <span id="totalText">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span></h5>
                    <input type="hidden" name="total_amount" id="totalAmount" value="{{ $invoice->total_amount }}">
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
            </div>
        </form>
    </section>
</div>
@endsection

@push('js')
<script>
    // Reuse dari script create.blade.php (hitung subtotal, total, tambah/hapus baris)
    $(document).ready(function() {
        let rowIndex = {{ count($invoice->items) }};

        function updateSubtotal(row) {
            let buying = parseFloat(row.find('.selling-price').val()) || 0;
            let qty = parseInt(row.find('.quantity').val()) || 1;
            let subtotal = buying * qty;
            row.find('.subtotal').val(subtotal);
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#totalText').text('Rp ' + total.toLocaleString('id-ID'));
            $('#totalAmount').val(total);
        }

        $('#addRow').click(function() {
            let newRow = `
                <tr>
                    <td>
                        <select name="products[${rowIndex}][product_id]" class="form-control select-product">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }} (Rp {{ number_format($prod->price, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="products[${rowIndex}][buying_price]" class="form-control buying-price" min="0" value="0"></td>
                    <td><input type="number" name="products[${rowIndex}][price]" class="form-control selling-price" min="0" value="0"></td>
                    <td><input type="number" name="products[${rowIndex}][quantity]" class="form-control quantity" min="1" value="1"></td>
                    <td><input type="number" name="products[${rowIndex}][subtotal]" class="form-control subtotal" readonly></td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
                </tr>`;
            $('#productTable tbody').append(newRow);
            rowIndex++;
        });

        $(document).on('input', '.selling-price, .quantity', function() {
            updateSubtotal($(this).closest('tr'));
        });

        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
            updateTotal();
        });
    });
</script>
@endpush