@extends('layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Invoice</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section>
        <form id="invoiceForm" action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <div class="card-body">
                {{-- 🔹 Info dasar invoice --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>No Invoice</label>
                        <input type="text" name="invoice_number" class="form-control"
                               value="INV-{{ date('YmdHis') }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Tanggal Invoice</label>
                        <input type="date" name="invoice_date" class="form-control"
                               value="{{ date('Y-m-d') }}">
                    </div>
                    <!-- <div class="col-md-3">
                        <label>Customer</label>
                        <select name="customer_id" class="form-control select2">
                            <option value="">-- Pilih Customer --</option>
                            @foreach ($customers as $cust)
                                <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                            @endforeach
                        </select>
                    </div> -->
                    <div class="col-md-3">
                        <label>Customer</label>
                        <select name="customer_id" class="form-control select">
                            <option value="">-- Pilih Customer --</option>
                            @foreach ($customers as $cust)
                                <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">-- Pilih Status --</option>
                            <option value="PAID">PAID</option>
                            <option value="PENDING">PENDING</option>
                        </select>
                    </div>
                </div>

                <hr>

                {{-- 🔹 Produk --}}
                <h6>Produk</h6>
                <input type="hidden" id="markup_value" value="{{ optional($settings->where('name', 'markup')->first())->value }}">
                <table class="table table-bordered" id="productTable">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 30%">Produk</th>
                            <th style="width: 15%">Harga Beli / unit</th>
                            <th style="width: 15%">Harga Jual / unit</th>
                            <th style="width: 10%">Qty</th>
                            <th style="width: 20%">Subtotal</th>
                            <th style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- <td>
                                <select name="products[0][product_id]" class="form-control select2 select-product">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($products as $prod)
                                        <option value="{{ $prod->id }}">
                                            {{ $prod->name }} (Rp {{ number_format($prod->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </td> -->
                            <td>
                                <select name="products[0][product_id]" class="form-control select-product">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($products as $prod)
                                        <option value="{{ $prod->id }}">
                                            {{ $prod->name }} (Rp {{ number_format($prod->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="products[0][buying_price]" class="form-control buying-price" min="0" step="0.01" value="0"></td>
                            <td><input type="number" name="products[0][price]" class="form-control selling-price" min="0" step="0.01" value="0"></td>
                            <td><input type="number" name="products[0][quantity]" class="form-control quantity" min="0.01" step="0.01" value="1"></td>
                            <td><input type="number" name="products[0][subtotal]" class="form-control subtotal" readonly></td>
                            <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
                        </tr>
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

                {{-- 🔹 Total --}}
                <div class="text-right">
                    <h5>Total: <span id="totalText">Rp 0</span></h5>
                    <input type="hidden" name="total_amount" id="totalAmount">
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
@push('js')
<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: '-- Pilih --',
        allowClear: true
    });

    let rowIndex = 1;

    // 🔸 Fungsi update subtotal per baris
    function updateSubtotal(row) {
        let selling = parseFloat(row.find('.selling-price').val()) || 0;
        let qty = parseFloat(row.find('.quantity').val()) || 0;
        let subtotal = selling * qty;
        row.find('.subtotal').val(subtotal.toFixed(2));
        updateTotal();
    }

    // 🔸 Fungsi update total semua baris
    function updateTotal() {
        let total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#totalText').text('Rp ' + total.toLocaleString('id-ID'));
        $('#totalAmount').val(total.toFixed(2));
    }

    // 🔸 Saat pilih produk → ambil data harga dari API
    $(document).on('change', '.select-product', function() {
        let productId = $(this).val();
        let row = $(this).closest('tr');

        if (!productId) {
            row.find('.selling-price').val('');
            updateSubtotal(row);
            return;
        }

        $.ajax({
            url: `/api/products/${productId}`,
            type: 'GET',
            success: function(response) {
                row.find('.selling-price').val(response.price ?? 0);
                updateSubtotal(row);
            },
            error: function() {
                row.find('.selling-price').val(0);
                updateSubtotal(row);
            }
        });
    });

    // 🔸 Saat harga jual atau qty berubah → hitung subtotal
    $(document).on('input', '.selling-price, .quantity', function() {
        let row = $(this).closest('tr');
        updateSubtotal(row);
    });

    // 🔸 Tambah baris produk baru
    $('#addRow').click(function() {
        let newRow = `
            <tr>
                <td>
                    <select name="products[${rowIndex}][product_id]" class="form-control select-product">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $prod)
                            <option value="{{ $prod->id }}">
                                {{ $prod->name }} (Rp {{ number_format($prod->price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="products[${rowIndex}][buying_price]" class="form-control buying-price" min="0" step="0.01" value="0"></td>
                <td><input type="number" name="products[${rowIndex}][price]" class="form-control selling-price" min="0" step="0.01" value="0"></td>
                <td><input type="number" name="products[${rowIndex}][quantity]" class="form-control quantity" min="0.01" step="0.01" value="1"></td>
                <td><input type="number" name="products[${rowIndex}][subtotal]" class="form-control subtotal" readonly></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
            </tr>`;
        $('#productTable tbody').append(newRow);
        rowIndex++;
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: '-- Pilih --',
            allowClear: true
        });
    });

    // 🔸 Hapus baris
    $(document).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
        updateTotal();
    });

    // 🔸 Validasi markup
    const markupValue = parseFloat($('#markup_value').val()) || 0;

    $(document).on('input', '.selling-price', function() {
        const row = $(this).closest('tr');
        const sellingPrice = parseFloat($(this).val()) || 0;
        const buyingPrice = parseFloat(row.find('.buying-price').val()) || 0;
        const recommended = buyingPrice * (1 + markupValue / 100);

        // Tandai merah kalau harga jual < rekomendasi
        if (sellingPrice > recommended) {
            $(this).addClass('is-invalid').css('border-color', 'red');
        } else {
            $(this).removeClass('is-invalid').css('border-color', '');
        }

        updateSubtotal(row);
    });

    // 🔸 Update total terakhir sebelum submit
    $('#invoiceForm').on('submit', function() {
        updateTotal();
    });
});
</script>
@endpush