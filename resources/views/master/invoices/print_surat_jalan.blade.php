<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/dist/img/AdminLogo2.png') }}"> -->
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
            margin: 20px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
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
        .text-right { 
            text-align: right; 
        }
        .header { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            margin-bottom: 2px;
        }
        .company-name { 
            flex: 1; 
            text-align: left; 
            font-weight: bold; 
            font-size: 10px; 
        }
        .title { 
            flex: 1; 
            text-align: right; 
            font-size: 16px; 
        }
        hr.header-line { 
            border: 1px solid #000; 
            margin: 2px 0 10px 0; 
        }
        .info-table td { 
            border: none; 
            padding: 0; 
        }
        .signature-table td { 
            border: none; 
            padding-top:  5px; /* spasi untuk tanda tangan */ 
            text-align: center;
        }
        .notes-table td { 
            border: 1px solid #000; 
            padding: 6px; 
            vertical-align: top;
        }
    </style>    
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="company-name">
            Mikha Surya Kencana<br>
            Jl. Bukit I no. 15A Bukit Sariwangi
        </div>
        <div class="title">
            SURAT JALAN
        </div>
    </div>

    <!-- Garis bawah header -->
    <hr class="header-line">

    <!-- Baris info: Kepada dan No. Invoice -->
    <table class="info-table" style="width: 100%; margin-bottom: 10px; font-size: 12px; border-collapse: collapse;">
        <tr>
            <td><strong>Kepada:</strong> {{ $invoice->customer->name }}</td>
            <td style="text-align: right;"><strong>No. Invoice:</strong> {{ $invoice->invoice_number }}</td>
        </tr>
        <tr>
            <td><strong>Alamat:</strong> {{ $invoice->customer->address }}</td>
        </tr>
    </table>

    <!-- Tabel produk -->
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Banyak</th>
                <th>Checklist</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                <td class="text-right"></td>
            </tr>
            @endforeach

            <!-- Row Catatan & Perhatian -->
            <tr>
                <td colspan="3">
                    <table class="notes-table" style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 50%;"><strong>Catatan:</strong><br></td>
                            <td style="width: 50%;">
                                <strong>Perhatian:</strong><br>
                                1. Surat jalan ini merupakan bukti resmi pengiriman barang.<br>
                                2. Surat jalan ini bukan bukti penjualan.<br>
                                3. Surat jalan ini akan dilengkapi invoice sebagai bukti penjualan.
                            <br></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Tanda tangan -->
    <!-- <table class="signature-table" style="width: 100%; margin-top: 2px;">
        <tr>
            <td>Penerima / Pembeli</td>
            <td>Pengirim / Petugas Pelaksana</td>
        </tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr>
            <td>________________________________</td>
            <td>________________________________</td>
        </tr>
    </table> -->
    <table class="signature-table" style="width: 100%; margin-top: 40px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; text-align: center;">
            Penerima / Pembeli<br><br><br><br><br><br><br><br><br><br><br><br>
            ________________________________
        </td>
        <td style="width: 50%; text-align: center;">
            Pengirim / Petugas Pelaksana<br><br><br><br><br><br><br><br><br><br><br><br>
            ________________________________
        </td>
    </tr>
</table>

</body>
</html>
