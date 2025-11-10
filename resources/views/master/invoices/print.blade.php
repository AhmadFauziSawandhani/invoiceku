<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .header { text-align: left; margin-bottom: 20px; }
        .total { font-weight: bold; font-size: 14px; }
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
    <!-- <div class="header">
        <h2>INVOICE</h2>
        <p>No: {{ $invoice->invoice_number }}</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
        <p>{{ $invoice->customer->name }}</p>
    </div> -->

    <div class="header">
        <div class="company-name">
            <!-- <div style="margin-right: 10px;">
        <img src="{{ asset('../../../../../../public/assets/dist/img/AdminLogo.png')}}" alt="Logo" style="height: 50px;">
    </div> -->
            Mikha Surya Kencana<br>
            Jl. Bukit I no. 15A Bukit Sariwangi
        </div>
        <div class="title">
            INVOICE
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
    
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga / Unit</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                <td class="text-right">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-right total" style="margin-top: 10px;">
        Total: Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
    </p>
    <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <tr>
            <!-- Detail Pembayaran -->
            <td style="width: 50%; vertical-align: top;">
                <strong>Detail Pembayaran:</strong><br>
                Pembayaran dilakukan melalui transfer ke rekening:<br><br>
                Nama : Mikha Surya Kencana<br>
                No. Rek : 1320033225559<br>
                Bank : Mandiri
            </td>

            <!-- Tanda Tangan -->
            <td style="width: 50%; text-align: center; vertical-align: top; border: none;">
                <strong>Hormat Kami</strong><br><br><br><br><br><br><br><br><br><br><br><br>
                Nur Aisyah Syarif<br>
                ____________________________
            </td>
        </tr>
    </table>
</body>
</html>