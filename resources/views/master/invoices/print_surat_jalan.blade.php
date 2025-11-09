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
        .header { text-align: center; margin-bottom: 20px; }
        .total { font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SURAT JALAN</h2>
        <p>No: {{ $invoice->invoice_number }}</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
    </div>

    <p><strong>Kepada:</strong> {{ $invoice->customer->name }}</p>

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
        </tbody>
    </table>

    <p class="text-right total" style="margin-top: 10px;">
        Tandan Tangan
    </p>
</body>
</html>