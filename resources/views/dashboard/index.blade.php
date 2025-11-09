@extends('layout.app')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="m-0">
                TANGGAL {{ (new \App\Helpers\GeneralHelpers())->date_format_id(date('Y-m-d')) }}
            </h1>
            <ol class="breadcrumb float-sm-right mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>

    <!-- Dashboard Boxes -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Keuntungan Hari Ini -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ (new \App\Helpers\GeneralHelpers())->currency($total_profit_all > 0 ? $total_profit_all : 0) }}</h3>
                            <p>KEUNTUNGAN</p>
                        </div>
                        <div class="icon"><i class="ion ion-cash"></i></div>
                    </div>
                </div>

                <!-- Rekening Gaji Bulanan -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gray-light">
                        <div class="inner">
                            <h3>{{ (new \App\Helpers\GeneralHelpers())->currency($total_profit_month > 0 ? $total_profit_month: 0) }}</h3>
                            <p>KEUNTUNGAN BULAN INI</p>
                        </div>
                        <div class="icon"><i class="ion ion-cash"></i></div>
                    </div>
                </div>

                <!-- Total Invoice Hari Ini -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ (new \App\Helpers\GeneralHelpers())->currency($total_profit_today > 0 ? $total_profit_today : 0) }}</h3>
                            <p>KEUNTUNGAN HARI INI</p>
                        </div>
                        <div class="icon"><i class="ion ion-cash"></i></div>
                    </div>
                </div>

                <!-- Total Pengeluaran -->
                
            </div>

            <!-- Pemesanan Hari Ini -->
            <div class="card mt-3">
                <!-- <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <span>Pemesanan Hari Ini</span>
                    <a href="{{ route('dashboard.print') }}" class="btn btn-light btn-sm" target="_blank">
                        <i class="fa fa-print"></i> Print
                    </a>
                </div> -->
                <div class="card-header bg-info text-white">
                    <div class="row">
                        <div class="col-md-6">
                            <span>Pemesanan Hari Ini</span>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('dashboard.print') }}" class="btn btn-light btn-sm" target="_blank">
                                <i class="fa fa-print"></i> Print
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($invoices_today->count() > 0)
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices_today as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name ?? '-' }}</td>
                                        <td>{{ $item->total_qty }} {{ $item->product->unit ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-muted mb-0">Belum ada pesanan hari ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
