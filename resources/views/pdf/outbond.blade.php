<html lang="en" dir="ltr">

<head>
    <style>
        body {
            font-size: 12pt;
            font-family: 'consola';

        }

        .border-td {
            border: 1pt solid black;
        }

        table {
            border-collapse: collapse
        }

        td,
        th {
            padding: 2px;
        }

        #infoPat table {
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        .content-table tr th,
        .content-table tr td {
            border: 1px solid;
            padding: 2px;
        }

        p {
            font-size: 12pt !important;
        }
    </style>
</head>

<body id="bodyCetak" style="background-color:#fff;position:relative">
    {{-- <img width="185" height="75" alt="image" src="var:myvariable" style="margin-top: -40px;" /> --}}
    <div style="background-color:#fff;width:100%;height:297mm">
        <table style="width: 100%;">
            <thead style="width: 100%;">
                <tr>
                    <td style="position:relative;width:32mm;height:32mm;">
                        <div style="position:relative;width:32mm;height:32mm;display:flex">
                            <img style="margin: auto;position: relative;object-fit: contain;width: 6cm;height: 3.5cm;"
                                src="var:myvariable" alt="">
                        </div>
                    </td>
                    <td style="text-align:center;width: 100%;">
                        <h2 style="margin:0;">MAS CARGO EXPRESS</h2>
                        <h3 class="nk-size-pw" style="margin-top:0;margin-bottom:10px;line-height:1">
                            MULTI ANGKASA SOLUSI
                        </h3>
                        <p style="text-align: center">
                            Jl. Halim Perdana Kusuma No. 04, RT. 01 / RW. 02, Kel. Pajang, Kec. Benda, Kota Tangerang –
                            Banten 15126 <br>
                            Melayani Jasa Pengiriman Paket, Motor, Mobil, Pindahan Rumah, Pindahan kantor, Sewa Truck,
                            Kontainer, Distribusi Barang & Logistik
                        </p>
                        <p style="text-align: center;font-weight: bold;">
                            CSO : 08111 886 885 - 0811 886 486
                        </p>
                    </td>
                </tr>
                <tr style="border-bottom: 5px solid;">
                    <td colspan="3">
                        <hr style="margin: 2px;height: 5px;color:#000">
                        <hr style="margin-top: 0px;height: 2px;color:#000">
                    </td>
                </tr>
            </thead>
            <tbody style="width: 100%;">
                <tr>
                    <td colspan="4" style="margin:0px 1cm;padding-top:10px;box-sizing:border-box;text-align:center;">
                        <div style="width: 100%;text-align:center;line-height:2">
                            <div style="text-align:center;line-height:2">
                                <h3 style="line-height:2;">DATA MANIFEST OUTBOND</h3>
                            </div>
                            <div style="font-size:12pt;margin:10px;">
                                Tanggal : {{ $outbond->date_outbond }}</div>
                        </div>
                        <br>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- <div class="w-full" style="font-size: 11pt;">
            <br>
            <p style="text-align:justify;padding:0;margin:0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Sehubungan dengan
                nota dinas dari ketua
                Logistik, maka mohon kiranya bantuan mengeluarkan
                Barang Milik Negara Tim Kerja
            </p>
            <p style="text-align:justify;padding:0;margin:0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adapun
                rincian
                sebagai berikut:</p>
        </div> --}}
        <table cellspacing="0" cellpadding="5"
            style="width:100%;margin-top:10px;font-size: 8pt;border:1px solid black;">
            <thead style="border:1px solid;">
                <tr style="text-align: center;">
                    <th class="text-center" style="border:1px solid;">No STT</th>
                    <th class="text-center" style="border:1px solid;">Pengirim</th>
                    <th class="text-center" style="border:1px solid;">TUJUAN</th>
                    <th class="text-center" style="border:1px solid;">VENDOR</th>
                    <th class="text-center" style="border:1px solid;">Total Koli</th>
                    <th class="text-center" style="border:1px solid;width: 20%;">Total Kilo</th>
                    <th class="text-center" style="border:1px solid;width: 20%;">Total Volume</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outbond->manifest as $item)
                    <tr style="background-color: yellow;">
                        <td class="text-center" style="border:1px solid;">{{ $item->receipt_number }}</td>
                        <td class="text-center" style="border:1px solid;">{{ $item->shipping_name }}</td>
                        <td class="text-center" style="border:1px solid;">{{ $item->destination }}</td>
                        <td class="text-center" style="border:1px solid;">{{ $item->vendor_name }}</td>
                        <td class="text-center" style="border:1px solid;">{{ $item->total_colly }}</td>
                        <td class="text-center" style="border:1px solid;">{{ $item->total_actual }}</td>
                        <td class="text-center" style="border:1px solid;">{{ $item->total_volume }}</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="border:1px solid;color:green" colspan="7">
                            {{$item->note}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border:1px solid;padding:0 !important;">
                            <table style="width: 100%; margin: 5px 0px;" cellspacing="0" cellpadding="3">
                                <thead>
                                    <tr>
                                        <th colspan="10" style="font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">
                                            Rincian Barang
                                        </th>
                                    </tr>
                                    <tr>
                                        <th scope="col" rowspan="2"
                                            style="vertical-align: middle;font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">Nama
                                            Barang</th>
                                        <th scope="col" rowspan="2"
                                            style="vertical-align: middle;font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">
                                            Koli (Colly)</th>
                                        <th scope="col" rowspan="2"
                                            style="vertical-align: middle;font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">
                                            Kilo</th>
                                        <th scope="col" colspan="3" style="font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">Dimensi</th>
                                        <th scope="col" rowspan="2"
                                            style="vertical-align: middle;font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">
                                            Volume</th>
                                        <th scope="col" rowspan="2"
                                            style="vertical-align: middle;font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">
                                            Volume M3</th>
                                        <th scope="col" rowspan="2"
                                            style="vertical-align: middle;font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">
                                            Actual</th>
                                        <th scope="col" rowspan="2"
                                            style="vertical-align: middle;font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">
                                            Chargeable Weight
                                        </th>
                                    </tr>
                                    <tr>
                                        <th scope="col" style="font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">P</th>
                                        <th scope="col" style="font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">L</th>
                                        <th scope="col" style="font-size: 8pt;border:1px solid;"
                                            class="text-center border-td">T</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->product as $product)
                                        <tr>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->product_name }}</td>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->colly }}</td>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->weight }}</td>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->dimension_p }}</td>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->dimension_l }}</td>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->dimension_t }}</td>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->volume }}</td>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->volume_m3 }}</td>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->actual }}</td>
                                            <td class="text-center border-td" style="font-size:8pt;border:1px solid">
                                                {{ $product->chargeable_weight }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center">TOTAL Rincian</td>
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center font-weight-bold">
                                            {{ $item->total_colly }}</td>
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center font-weight-bold"> </td>
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center font-weight-bold">
                                        </td>
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center font-weight-bold">
                                        </td>
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center font-weight-bold">
                                        </td>
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center font-weight-bold">
                                            {{ $item->total_volume }}</td>
                                        </td>
                                        {{-- m3  --}}
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center font-weight-bold">
                                            {{ $item->total_volume_m3 }}
                                        </td>
                                        {{-- actual  --}}
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center font-weight-bold">
                                            {{ $item->total_actual }}
                                        </td>
                                        {{-- charge weight  --}}
                                        <td style="font-size: 8pt;border:1px solid black;"
                                            class="border-td text-center font-weight-bold">
                                            {{ $item->total_chargeable_weight }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                @endforeach
                    <tr style="background-color: yellow;">
                        <th class="border-td text-center" colspan="4">
                            Total
                        </th>
                        <th class="border-td text-center"> {{ $outbond->total_colly }}</th>
                        <th class="border-td text-center"> {{ $outbond->total_weight }}</th>
                        <th class="border-td text-center"> {{ $outbond->total_volume }}</th>
                    </tr>
            </tbody>
        </table>
        <table style="width:100%;font-size:11pt;margin-top:20px" cellspacing="4" celpadding="5">
            <tr>
                <td style="text-align: right;margin:20px 0;">
                    <h5 style="font-weight: normal;margin-bottom:10px;">Tangerang, {{\Carbon\Carbon::parse($outbond->date_outbond)->format('d F Y')}}</h5>
                </td>
            </tr>
        </table>
        <table style="width:100%;font-size:11pt;margin-top:20px" cellspacing="4" celpadding="5">
            <tr>
                <td style="width:33%;text-align: center;">
                    <h5 style="margin-bottom:0;font-weight: normal;">Admin Tally</h5>
                    <br><br><br>
                    <br><br><br>
                    <b>
                        <h5>
                            {{$outbond->createdBy->full_name}}
                        </h5>
                    </b>
                </td>
                <td style="width:33%;text-align: center;">
                    <h5 style="margin-bottom:0;font-weight: normal;">SUPIR COD</h5>
                    <br><br><br>
                    <br><br><br>
                    <b>
                        <h5>
                            {{$outbond->driver}}
                        </h5>
                    </b>
                </td>
                <td style="width:33%;text-align: center;">
                    <h5 style="margin-bottom:0;font-weight: normal;">Mengetahui</h5>
                    <br><br><br>
                    <br><br><br>
                    <b>
                        <h5>
                            {{$outbond->acknowledge}}
                        </h5>
                    </b>
                </td>
            </tr>
        </table>
    </div>
</body>
<!--
<script type="text/javascript">
    window.print();
</script>  -->

</html>
