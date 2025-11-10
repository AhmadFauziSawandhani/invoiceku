<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian {{ $today }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .title { 
            flex: 1; 
            text-align: right; 
            font-size: 16px; 
        }
        .company-name { 
            flex: 1; 
            text-align: left; 
            font-weight: bold; 
            font-size: 10px; 
        }
        .header { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            margin-bottom: 2px;
        }
        hr.header-line { 
            border: 1px solid #000; 
            margin: 2px 0 10px 0; 
        }
        .info-table td { 
            border: none; 
            padding: 0; 
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">
            <!-- <div style="margin-right: 10px;">
        <img src="{{ asset('../../../../../../public/assets/dist/img/AdminLogo.png')}}" alt="Logo" style="height: 50px;">
    </div> -->
            Mikha Surya Kencana<br>
            Jl. Bukit I no. 15A Bukit Sariwangi
        </div>
        <div class="title">
            Pemesanan Hari Ini
        </div>
    </div>

    <!-- Garis bawah header -->
    <hr class="header-line">

    <!-- Baris info: Kepada dan No. Invoice -->
    <table class="info-table" style="width: 100%; margin-bottom: 10px; font-size: 12px; border-collapse: collapse;">
        <tr>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</p>
        </tr>
    </table>
    <!-- <h2>Laporan Penjualan Harian</h2>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</p> -->

    @if($items_summary_today->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Total Quantity</th>
                    <th>Customer Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items_summary_today as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['product']->name ?? '-' }}</td>
                        <td>{{ $item['total_qty'] }} {{ $item['product']->unit ?? '' }}</td>
                        <td>
                            @foreach($item['customers'] as $cust)
                                <strong>{{ $cust['customer_name'] }}</strong>:
                                {{ $cust['quantity'] }} {{ $cust['unit'] }}<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada transaksi hari ini.</p>
    @endif
</body>
</html>
