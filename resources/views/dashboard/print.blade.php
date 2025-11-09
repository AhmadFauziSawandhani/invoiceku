<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan Hari Ini</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            color: #000;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11pt;
        }
    </style>
</head>
<body>

    <h2>Laporan Pemesanan Hari Ini<br>
        <small>{{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</small>
    </h2>

    @if($invoices->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 10%">No</th>
                <th>Nama Produk</th>
                <th style="width: 20%">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name ?? '-' }} </td>
                <td>{{ $item->total_qty }} {{ $item->product->unit ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align:center; margin-top:20px;">Tidak ada pesanan hari ini.</p>
    @endif
</body>
</html>