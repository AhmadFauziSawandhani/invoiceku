@extends('layout.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Invoice #{{ $invoice->invoice_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary float-right">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- Info Invoice -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <div class="row">
                        <div class="col-md-6">
                            <span>Informasi Invoice</span>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-light btn-sm" target="_blank">
                                <i class="fa fa-print"></i> Print Invoice
                            </a>
                            <a href="{{ route('invoices.jalan', $invoice->id) }}" class="btn btn-light btn-sm" target="_blank">
                                <i class="fa fa-print"></i> Print Surat Jalan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>No. Invoice:</strong> {{ $invoice->invoice_number }}</p>
                    <p><strong>Customer:</strong> {{ $invoice->customer->name ?? '-' }}</p>
                    <p><strong>Tanggal Invoice:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}</p>
                    <p><strong>Status:</strong>
                        @if(strtoupper($invoice->status) == 'PAID')
                            <span class="badge badge-success">{{ $invoice->status }}</span>
                        @elseif(strtoupper($invoice->status) == 'PENDING')
                            <span class="badge badge-warning">{{ $invoice->status }}</span>
                        @else
                            <span class="badge badge-secondary">{{ $invoice->status }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Detail Item -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Detail Item
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Subtotal</th>
                                <th>Keuntungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalProfit = $invoice->profit ?? 0; @endphp

                            @foreach($invoice->items as $index => $item)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product->name ?? '-' }}</td>
                                    <td>{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                                    <td>Rp {{ number_format($item->buying_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format(($item->selling_price - $item->buying_price) * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="text-center fw-bold bg-light">
                                <td colspan="5">Total</td>
                                <td>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($invoice->profit, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Total Profit -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5><strong>Total Keuntungan Invoice: </strong> Rp {{ number_format($totalProfit, 0, ',', '.') }}</h5>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
