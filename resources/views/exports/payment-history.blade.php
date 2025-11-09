<table style="width: 100%;border: 1px solid black" border="1">
    <thead>
        <tr>
            <th colspan="7">MasExpress.id</th>
        </tr>
        <tr>
            <th colspan="7">Data Laporan Payment History {{ $vendor->name }}</th>
        </tr>
        <tr>
            <th colspan="7">Per Tanggal: {{ \Carbon\Carbon::parse($dateStart)->format('d-m-Y') }} -
                {{ \Carbon\Carbon::parse($dateEnd)->format('d-m-Y') }}</th>
        </tr>
        <tr>
            <th align="center" height="30" width="20">Nomor Invoice</th>
            <th align="center" height="30" width="15">Tipe</th>
            <th align="center" height="30" width="15">Tanggal</th>
            <th align="center" height="30" width="15">Jatuh Tempo</th>
            <th align="center" height="30" width="30">IDR Invoice <br> Rp.
                {{ number_format($totalInvoice, 0, ',', '.') }}</th>
            <th align="center" height="30" width="30">IDR Payment <br> Rp.
                {{ number_format($totalPayment, 0, ',', '.') }}</th>
            <th align="center" height="30" width="30">IDR Saldo <br> Rp.
                {{ number_format($totalSaldo, 0, ',', '.') }}</th>
            <th align="center" height="30" width="15">Remark</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $payment)
            @php
                $date = Carbon\Carbon::parse($payment['due_date']);
                $now = Carbon\Carbon::now();
            @endphp
            @if ($now->diffInDays($date) > 0)
                <tr style="background-color: red">
                    <td style="background-color: red">{{ $payment['invoice_no'] }}</td>
                    <td style="background-color: red">{{ $payment['type'] }}</td>
                    <td style="background-color: red">{{ $payment['date'] }}</td>
                    <td style="background-color: red">{{ $payment['due_date'] }}</td>
                    <td style="background-color: red">{{ $payment['invoice_amount'] }}</td>
                    <td style="background-color: red">{{ $payment['payment_amount'] }}</td>
                    <td style="background-color: red">{{ $payment['saldo'] }}</td>
                    <td style="background-color: red">{{ $payment['remark'] }}</td>
                </tr>
            @else
                <tr>
                    <td>{{ $payment['invoice_no'] }}</td>
                    <td>{{ $payment['type'] }}</td>
                    <td>{{ $payment['date'] }}</td>
                    <td>{{ $payment['due_date'] }}</td>
                    <td>{{ $payment['invoice_amount'] }}</td>
                    <td>{{ $payment['payment_amount'] }}</td>
                    <td>{{ $payment['saldo'] }}</td>
                    <td>{{ $payment['remark'] }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
    </tfoot>
</table>
