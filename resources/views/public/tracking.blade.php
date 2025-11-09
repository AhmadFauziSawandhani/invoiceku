@extends('layout.public')

@section('title', 'Tracking Nomor Resi')

@push('css')
    <style>
        .timeline {
            list-style: none;
            padding: 20px 0 20px;
            position: relative;
        }

        .timeline:before {
            top: 80px;
            bottom: 90px;
            position: absolute;
            content: " ";
            width: 3px;
            background-color: #eeeeee;
            left: 10%;
            margin-left: -1.5px;
        }

        .timeline>li {
            margin-bottom: 20px;
            position: relative;
        }

        .timeline>li:before,
        .timeline>li:after {
            content: " ";
            display: table;
        }

        .timeline>li:after {
            clear: both;
        }

        .timeline>li:before,
        .timeline>li:after {
            content: " ";
            display: table;
        }

        .timeline>li:after {
            clear: both;
        }

        .timeline>li>.timeline-panel {
            width: 76%;
            float: left;
            border-radius: 2px;
            padding: 20px;
            position: relative;
        }

        .timeline>li>.timeline-badge {
            color: #fff;
            width: 50px;
            height: 50px;
            line-height: 50px;
            font-size: 1.4em;
            text-align: center;
            position: absolute;
            top: 33%;
            left: 10%;
            margin-left: -25px;
            background-color: #999999;
            z-index: 100;
            border-top-right-radius: 50%;
            border-top-left-radius: 50%;
            border-bottom-right-radius: 50%;
            border-bottom-left-radius: 50%;
        }

        .timeline>li.timeline-inverted>.timeline-panel {
            margin-left: 1rem;
        }

        .timeline>li.timeline-inverted>.timeline-panel:before {
            border-left-width: 0;
            border-right-width: 15px;
            left: -15px;
            right: auto;
        }

        .timeline>li.timeline-inverted>.timeline-panel:after {
            border-left-width: 0;
            border-right-width: 14px;
            left: -14px;
            right: auto;
        }

        .timeline-heading {
            width: 70%;
        }

        .timeline-clock {
            width: 25%;
        }

        .timeline-title {
            margin-top: 0;
            color: inherit;
        }

        .timeline-body>p,
        .timeline-body>ul {
            margin-bottom: 0;
        }

        .timeline-body>p+p {
            margin-top: 5px;
        }

        .flex-container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
        }

        .flex-around {
            -ms-flex-pack: distribute;
            justify-content: space-around;
        }

        .flex-between {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }

        .flex-start {
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start;
        }

        .flex-end {
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end;
        }

        .flex-around {
            -ms-flex-pack: distribute;
            justify-content: space-around;
        }

        .flex-center {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .align-items-center {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }
    </style>
@endpush

@section('content')

    <div class="container">
        <div class="d-flex justify-content-center p-10">
            <div class="col-lg-12 d-flex flex-column">
                <img src="{{ asset('AdminLogo.png') }}" class="img-fluid mx-auto text-center" style="max-width: 230px;"
                    alt="">
                <h1 style="color: #dc0b0c;font-weight: 800;" class="mx-auto text-center"><span style="color: #104e97;">Cek Resi
                    </span>Paketmu</h1>
                <form action="{{ route('tracking-page') }}" method="GET">
                    @if (!$tracking && !$resi)
                        <div class="card text-center mx-auto" style="border-radius: 35px;width: 920px;">
                            <div class="card-header">
                                Masukkan nomor resi pengiriman barang kamu.
                            </div>
                            <div class="card-body">
                                <input class="form-control form-control-lg" name="no_resi" type="text" placeholder="Nomor Resi"
                                    aria-label="Nomor Resi">
                            </div>
                            <div class="card-footer d-grid  text-muted">
                                <button type="submit" class="btn btn-primary btn-block"
                                    style="border-radius: 35px;">Lacak</button>
                            </div>
                        </div>
                    @endif
                </form>
                @if ($tracking && $resi)
                    <div class="card">
                        <div class="card-header d-flex flex-lg-row flex-sm-column align-items-center justify-content-between">
                            <a href="{{ route('tracking-page') }}" class="btn btn-sm btn-transparent"><i class="fa fa-arrow-left"></i>Kembali</a>
                            <h4>No Resi: {{ $tracking->receipt_number }}</h4>
                        </div>
                        <div class="card-body d-flex flex-lg-row flex-sm-column">
                            <div class="col-sm-12 col-lg-4">
                                <h4 class="text-left">Alamat Pengiriman</h4>
                                <div class="d-flex flex-column">
                                    <h5>{{ $tracking->shipping_name }}</h5>
                                    <p class="mb-auto">{{ $tracking->phone_number }}</p>
                                    <p class="mb-auto">{{ $tracking->shipping_address }}</p>
                                    <p class="mb-auto">{{ $tracking->shipping_city }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-8">
                                <ul class="timeline">
                                    @foreach ($tracking->tracking->reverse() as $item)
                                        <li class="timeline-inverted">
                                            <div class="timeline-badge d-flex align-items-center justify-content-center">
                                                <i class="fa fa-1x fa-check"></i>
                                            </div>
                                            <div class="timeline-panel">
                                                <div class="flex-container flex-around">
                                                    <div class="timeline-heading">
                                                        <p class="mb-auto"><strong>{{ $item->status }}</strong></p>
                                                        <p class="mb-auto">
                                                            {{ date('d-M-Y', strtotime($item->tracking_date)) }}
                                                        </p>
                                                    </div>
                                                    <div class="timeline-heading">
                                                        <p>{{ $item->note }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @elseif(!$tracking && $resi)
                    <div class="text-center">Resi Tidak ditemukan</div>
                @endif
            </div>
        </div>
    </div>
@endsection
