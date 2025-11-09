<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="fajri" />
    <style type="text/css">
        .s1 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 8pt;
        }

        .s2 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 6pt;
        }

        .s3 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 7pt;
        }

        .s4 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        .s6 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 10pt;
        }

        .s8 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: underline;
            font-size: 6pt;
        }

        .s9 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 6pt;
        }

        .s10 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 4pt;
        }

        .s11 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 5pt;
        }

        .s12 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 5pt;
        }

        .s13 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 6pt;
            vertical-align: 1pt;
        }

        .s14 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 3pt;
        }

        p {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 4pt;
            margin: 0pt;
        }

        .s15 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 10pt;
        }

        li {
            display: block;
        }

        #l1 {
            padding-left: 0pt;
            counter-reset: c1 0;
        }

        #l1>li:before {
            counter-increment: c1;
            content: counter(c1, decimal)". ";
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 5pt;
        }

        li {
            display: block;
        }

        #l2 {
            padding-left: 0pt;
            counter-reset: d1 0;
        }

        #l2>li:before {
            counter-increment: d1;
            content: counter(d1, decimal)". ";
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 5pt;
        }

        li {
            display: block;
        }

        #l3 {
            padding-left: 0pt;
            counter-reset: e1 0;
        }

        #l3>li:before {
            counter-increment: e1;
            content: counter(e1, decimal)". ";
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 5pt;
        }

        td>p {
            display: inline;
        }

        .red-stroke {
            border-bottom: 1px solid black;
        }
        .top-stroke {
            border-top: 1px solid black;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
</head>

<body>
    @for ($index = 0; $index < 3; $index++)
    {{-- lembar 1 --}}
    <table style="border-collapse:collapse;" width="100%" cellspacing="0" cellpadding="3" autosize>
        <tr>
            <td class="text-center" style="width: 130px;">
                <div>
                    <img src="{{$qrCodeBase64}}" width="85" height="85" style="border-radius: 20%;"
                    alt="barcode" />
                </div>
                <small class="text-center s1" style="font-size: 8px; text-align: center;margin: auto">scan here to check the delivery status</small>
            </td>
            <td class="text-center">
                <h2 style="color: #dd0b0b">mascargo<span style="color: #114e9a">express.com</span></h2>
                <p class="s3">Melayani Jasa Pengiriman Paket, Motor, Mobil, Alat Berat, <br> Pindahan Rumah, Pindahan kantor,
                    Sewa Truck, Kontainer, <br> Distribusi Barang & Logistik</p>
                <p class="s1">No STT / RESI : {{ $manifest->receipt_number }}</p>
            </td>
            <td style="text-align: right;width: 200px">
                <img width="130" height="45" alt="logo" src="var:myvariable" />
                <p class="s1 text-right">Head Office</p>
                <p class="s3 text-right">Jl. Halim Perdana Kusuma No. 04, RT. 01 / RW. 02,Kel. Pajang, Kec. Benda, Kota
                    Tangerang – Banten 15126</p>
            </td>
        </tr>
    </table>
    <table style="border-collapse:collapse;font-size: 11px;" width="100%" cellpadding="3" autosize>
        <tr>
            <td style="width: 300px" class="red-stroke">
                Jenis Pembayaran : {{$manifest->payment_type ? ($manifest->payment_type == 1 ? 'CASH' : 'TOP' ) : ''}}
            </td>

            <td class="red-stroke text-center">

            </td>
            <td class="red-stroke text-center">
                Layanan : {{$manifest->service_type}}
            </td>
        </tr>
        <tr>
            <td style="width: 250px;vertical-align: top">
                <table>
                    <tr>
                        <td>Pengirim</td>
                        <td>: {{ $manifest->shipping_name }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $manifest->shipping_address }}</td>
                    </tr>
                    <tr>
                        <td>Kota</td>
                        <td>: {{ $manifest->shipping_city }}</td>
                    </tr>
                    <tr>
                        <td>Telp</td>
                        <td>: {{ substr_replace($manifest->phone_number, 'XXXX', -4) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="s1" style="border: 1px solid red;"><strong>ISI BARANG TIDAK DI PERIKSA PETUGAS EKSPEDISI</strong></td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: top">
                <table>
                    <tr>
                        <td>Moda</td>
                        <td>: {{ $manifest->moda }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ $manifest->date_manifest }}</td>
                    </tr>
                    <tr>
                        <td>Berat</td>
                        <td>: {{ round($manifest->total_actual) }} Kg</td>
                    </tr>
                    <tr>
                        <td>Berat dikenakan</td>
                        <td>: {{ round($manifest->total_chargeable_weight) }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Koli</td>
                        <td>: {{ round($manifest->total_colly) }}</td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: top">
                <table>
                    <tr>
                        <td>Instruksi</td>
                        <td>: {{ $manifest->instruction_special ?? '---' }}</td>
                    </tr>
                      <tr>
                        <td>No DO/Reff</td>
                        <td>: {{$manifest->do_number ?? '---'}} </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>: {{ $manifest->recipient_detail }}</td>
                    </tr>
                    <tr>
                        <td>Marketing</td>
                        <td>: {{ $manifest->sales_name }}</td>
                    </tr>
                    <tr>
                        <td>Pickup</td>
                        <td>: {{ $manifest->drop_pickup }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 250px;vertical-align: top" class="top-stroke">
                <table>
                     <tr>
                        <td>Penerima / Perusahaan</td>
                        <td>: {{ $manifest->recipient_name ? $manifest->recipient_name : $manifest->recipient_company }}</td>
                    </tr>
                    <tr>
                        <td>Kota</td>
                        <td>: {{ $manifest->recipient_city }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top">Alamat</td>
                        <td>: {{ $manifest->recipient_address }}</td>
                    </tr>
                    <tr>
                        <td>Telp</td>
                        <td>: {{ $manifest->recipient_phone }}</td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: top" class="top-stroke">

            </td>
            <td style="vertical-align: top" class="top-stroke">

            </td>
        </tr>
    </table>
    <table style="border-collapse:collapse;font-size: 11px;margin-top: 15px" width="100%" cellpadding="3" autosize>
        <tr>
            <td style="width: 350px;"> <p class="s2">Dengan menyerahkan kiriman, Anda sudah sepenuhnya menyetujui ketentuan kebijakan dan kondisi milik MAS CARGO EXPRESS yang sudah tetera pada halaman website *(www.mascargoexpress.com/term-of-service)*</p>
            </td>
            <td class="text-center">
                <p class="s4"> ( Tally checker by : {{ $manifest->creaedUser->full_name }} ) </p>
            </td>
            <td class="text-center">
                <p class="s4"> ( {{ $manifest->recipient_name }} ) </p>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px dotted #000" colspan="3">Lembar {{ $index+1 }}: {{$index == 0 ? 'SHIPPER' :( $index == 1 ? 'OPERATIONAL' : 'BILLING') }} </td>
        </tr>
    </table>
    {{-- end lembar 1 --}}
    @endfor
</body>

</html>
