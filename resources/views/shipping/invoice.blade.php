<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rohim
 * Date: 9/12/2023
 * Time: 7:00 AM
 */
ini_set('pcre.backtrack_limit', '5000000');

$totalColly = 0;
$totalChargeableWeight = 0;
?>
<html>

<head>
    <style>
        .note {
            background-color: #b4c7e7;
            border-radius: 6px;
            border: 1px solid #b4c7e7;
            padding: 10px;
            margin: 10px;
            width: 100%;
        }

        .remove-border-td {
            border-style: hidden !important;
        }

        .border-td {
            border: 1pt solid black;
        }

        .border-l {
            border-left: 1px solid black;
        }

        .border-r {
            border-right: 1px solid black;
        }

        .border-tr {
            border-bottom: 1pt solid black;
            border-top: 1pt solid black;
        }

        .text-md {
            font-size: 15pt;
        }

        .text-center {
            text-align: center;
        }

        .w-full {
            width: 100%;
        }

        .col3 {
            width: 25%;
            float: left;
        }

        .col4 {
            width: 30%;
            float: left;
        }

        .col6 {
            width: 50%;
            float: left;
        }

        .col12 {
            width: 100%;
            float: left;
        }

        .col-height-md {
            line-height: 20px;
        }

        .border-left {
            border-left: 1px solid black;
        }

        .border-right {
            border-right: 1px solid black;
        }
    </style>
</head>

<body>
    <div class="w-full" style="padding:5px;border: 1px solid #000;">
        <table>
            <tr>
                <td class="remove-border-td" width="12%" style="text-align: right">
                    <img src="var:myvariable" style="width:50%;">
                </td>
                <td width="35%">
                    <table width="100%" style="font-size: 24pt;">
                        <tr>
                            <td>INVOICE</td>
                        </tr>
                        <tr>
                            <td>PT. MULTI ANGKASA SOLUSI (MAS) CARGO EXPRESS</td>
                        </tr>
                        <tr>
                            <td>Ruko Duta Garden, Jl. Husein Sastranegara No.07</td>
                        </tr>
                        <tr>
                            <td>RT.027/RW.008 Jurumudi, Benda Tangerang - Banten, Tlp. 021 - 22952468</td>
                        </tr>
                        <tr>
                            <td>Mail : info@mascargoexpress.com / mas.express@ymail.com</td>
                        </tr>
                        <tr>
                            <td>Web : www.mascargoexpress.com</td>
                        </tr>
                    </table>
                </td>
                <td class="remove-border-td" width="50%">
                    <table width="100%" style="border: 5px dashed #000000; font-size: 24pt;">
                        <tr>
                            <td colspan="3" style="color: red; text-align: center">CUSTOMER</td>
                        </tr>
                        <tr>
                            <td width="20%">NAMA</td>
                            <td>: {{ $data->shipping_name }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>ALAMAT</td>
                            <td>: {{ $data->shipping_address }}</td>
                            <td></td>
                        </tr>
                        <tr style="overflow: hidden; height: 12px; white-space: nowrap;">
                            <td colspan="3" style="line-height: 30px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>KOTA</td>
                            <td>: {{ $data->shipping_city }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>NO TLP / HP</td>
                            <td>: {{ $data->phone_number }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>MARKETING</td>
                            <td>: {{ $data->sales_name }}</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="margin:0;">
            <table autosize="1"
                style="border-collapse: collapse;font-size: 15pt; {{ count($images) > 0 ? '' : 'width: 100%;' }}">
                <thead>
                    <tr style="font-weight: bold;">
                        <th class="border-td text-md col-height-md" style="width: 3%;">NO</th>
                        <th class="border-td text-md" style="width: 10%;">DATE ISSUED</th>
                        <th class="border-td text-md" style="width: 10%;">MODA</th>
                        <th class="border-td text-md" style="width: 10%;">CONNOTE</th>
                        <th class="border-td text-md" style="width: 10%;">DEST</th>
                        <th class="border-td text-md" style="width: 10%;">COLLY</th>
                        <th class="border-td text-md" style="width: 10%;">CHARGEABLE <br> WEIGHT</th>
                        <th class="border-td text-md" style="width: 10%;">PRICE</th>
                        <th class="border-td text-md" style="width: 10%;">PICK UP/Dooring</th>
                        <th class="border-td text-md" style="width: 10%;">REPACKING</th>
                        <th class="border-td text-md" style="width: 10%;">INSURANCE</th>
                        <th class="border-td text-md" style="width: 10%;">FORKLIFT</th>
                        <th class="border-td text-md" style="width: 12%;">LALAMOVE/GRAB</th>
                        <th class="border-td text-md" style="width: 10%;">AMOUNT</th>
                        <th class="border-td text-md" style="width: 10%;">REMARKS</th>
                    </tr>
                </thead>
                <tbody style="font-size: 15pt;">
                    @foreach ($details as $key => $item)
                        <tr>
                            <td class="text-center text-md border-left" style="width: 4%;">{{ $key + 1 }}</td>
                            <td class="text-center text-md" style="width: 10%;">
                                {{ (new \App\Helpers\GeneralHelpers())->date_format_dmY($data->invoice_date) }}</td>
                            <td class="text-center text-md" style="width: 10%;">{{ $item->moda }}</td>
                            <td class="text-center text-md" style="width: 10%;">{{ $item->receipt_number }}</td>
                            <td class="text-center text-md" style="width: 10%;">{{ $item->destination }}</td>
                            <td class="text-center text-md" style="width: 10%;">{{ $item->colly }}</td>
                            <td class="text-center text-md" style="width: 10%;">{{ $item->chargeable_weight }}</td>
                            <td class="text-center text-md" style="width: 10%;">
                                {{ (new \App\Helpers\GeneralHelpers())->raw_currency($item->price) }}</td>
                            <td class="text-center text-md" style="width: 10%;">
                                {{ (new \App\Helpers\GeneralHelpers())->raw_currency($item->price_addons_pickup ? $item->price_addons_pickup : $item->price_addons_dooring) }}
                            </td>
                            <td class="text-center text-md" style="width: 10%;">
                                {{ (new \App\Helpers\GeneralHelpers())->raw_currency($item->price_addons_packing) }}
                            </td>
                            <td class="text-center text-md" style="width: 10%;">
                                {{ (new \App\Helpers\GeneralHelpers())->raw_currency($item->price_addons_insurance) }}
                            </td>
                            <td class="text-center text-md" style="width: 10%;">
                                {{ (new \App\Helpers\GeneralHelpers())->raw_currency($item->forklift) }}
                            </td>
                            <td class="text-center text-md" style="width: 10%;">
                                {{ (new \App\Helpers\GeneralHelpers())->raw_currency($item->lalamove_grab) }}
                            </td>
                            <td class="text-center text-md" style="width: 10%;">
                                {{ (new \App\Helpers\GeneralHelpers())->raw_currency($item->amount) }}</td>
                            <td class="text-center text-md border-right" style="width: 10%;">{{ $item->remarks }}</td>
                        </tr>
                        @php
                            if (isset($item->colly)) {
                                $totalColly += $item->colly;
                            }

                            if (isset($item->chargeable_weight)) {
                                $totalChargeableWeight += $item->chargeable_weight;
                            }
                        @endphp
                    @endforeach
                    @if ($row <= 5)
                        <tr>
                            <td colspan='15' class='border-left border-right' style='line-height: 15px;'>
                                &nbsp;
                            </td>
                        </tr>
                    @endif
                    @if(count($details) > 0 && $details[0]->manifest)
                        <tr>
                            <td colspan='15'>
                                @foreach ($details as $item)
                                @if($item->manifest)
                                    <tr>
                                        <th colspan="13" style="font-size: 8pt;background-color: yellow;">
                                            PACKING LIST RINCIAN TUJUAN {{$item->manifest->destination}} {{$data->shipping_name}} {{$item->manifest->receipt_number}}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">#</th>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">Nama
                                            Barang</th>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">
                                            Koli (Colly)</th>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">
                                            Kilo</th>
                                        <th scope="col" colspan="3" style="font-size: 8pt;background-color:#92d14f;" class="text-center border-td">Dimensi</th>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">
                                            Volume</th>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">
                                            Volume M3</th>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">
                                            Actual</th>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">
                                            Chargeable Weight
                                        </th>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">
                                            Packaging</th>
                                        <th scope="col" rowspan="2" style="vertical-align: middle;font-size: 8pt;background-color:#92d14f;" class="text-center border-td">Charge
                                            Packaging</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" style="font-size: 8pt;background-color:#92d14f;" class="text-center border-td">P</th>
                                        <th scope="col" style="font-size: 8pt;background-color:#92d14f;" class="text-center border-td">L</th>
                                        <th scope="col" style="font-size: 8pt;background-color:#92d14f;" class="text-center border-td">T</th>
                                    </tr>
                                    @foreach ($item->manifest->product as $product)
                                    <tr>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $loop->iteration }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->product_name }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->colly }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->weight }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->dimension_p }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->dimension_l }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->dimension_t }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->volume }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->volume_m3 }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->actual }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->chargeable_weight }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ $product->packaging }}</td>
                                        <td class="text-center border-td" style="font-size:8pt;">{{ number_format($product->charge_packaging) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" style="font-size: 8pt;" class="border-td text-center">{{$item->manifest->date_manifest}}</td>
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold">
                                            {{ $item->manifest->total_colly }}</td>
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold"> </td>
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold">
                                        </td>
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold">
                                        </td>
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold">
                                        </td>
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold">
                                            {{ $item->manifest->total_volume }}</td>
                                        </td>
                                        {{-- m3  --}}
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold">
                                            {{ $item->manifest->total_volume_m3 }}
                                        </td>
                                        {{-- actual  --}}
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold">
                                            {{ $item->manifest->total_actual }}
                                        </td>
                                        {{-- charge weight  --}}
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold">
                                            {{ $item->manifest->total_chargeable_weight }}</td>
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold"></td>
                                        <td style="font-size: 8pt;" class="border-td text-center font-weight-bold">
                                            {{ number_format($item->manifest->total_charge_packaging) }}
                                        </td>
                                    </tr>
                                @endif
                                @endforeach
                            </td>
                        </tr>
                        @if ($row <= 5)
                            <tr>
                                <td colspan='15' class='border-left border-right' style='line-height: 15px;'>
                                    &nbsp;
                                </td>
                            </tr>
                        @endif
                    @else
                        @foreach ($images as $image)
                            <tr>
                                <td colspan="15" class="border-left border-right">
                                    @php
                                        $img = base64_encode(file_get_contents('storage/' . $image->image));

                                        $ext = 'jpg';
                                        if (strpos($image->image, '.png') != false) {
                                            $ext = 'png';
                                        }

                                        if (strpos($image->image, '.jpg') != false) {
                                            $ext = 'jpg';
                                        }

                                        if (strpos($image->image, '.jpeg') != false) {
                                            $ext = 'jpeg';
                                        }
                                    @endphp
                                    <img src="data:image/{{ $ext }};base64,{{ $img }}"
                                        style="width: 1500px; height: 5000px" alt="">
                                </td>
                            </tr>
                        @endforeach
                        @for ($i = 1; $i <= $row; $i++)
                            @php
                                if ($row <= 2) {
                                    if ($imgRow == 0) {
                                        $height = 270;
                                    } elseif ($imgRow == 1) {
                                        $height = 210;
                                    } else {
                                        $height = 10;
                                    }
                                } elseif ($row <= 3) {
                                    if ($imgRow == 0) {
                                        $height = 80;
                                    } elseif ($imgRow == 1) {
                                        $height = 20;
                                    } else {
                                        $height = 1;
                                    }
                                } elseif ($row <= 5) {
                                    if ($imgRow == 0) {
                                        $height = 70;
                                    } elseif ($imgRow == 1) {
                                        $height = 50;
                                    } else {
                                        $height = 1;
                                    }
                                } elseif ($row <= 7) {
                                    if ($imgRow == 0) {
                                        $height = 10;
                                    } else {
                                        $height = 1;
                                    }
                                }
                            @endphp
                            @if ($row <= 5)
                                <tr>
                                    <td colspan="15" class="border-left border-right"
                                        style="line-height: {{ $height }}px;">
                                        &nbsp;
                                    </td>
                                </tr>
                            @endif
                        @endfor
                    @endif
                </tbody>
                <tfoot>
                    <tr style="font-size: 31pt;">
                        <td class="border-td" colspan="5"
                            style="font-size: 15pt;text-align: center;font-weight:bold; line-height: 32px"><b>TOTAL</b>
                        </td>
                        <td class="border-td text-center" style="font-size: 15pt; line-height: 32px">
                            {{ $totalColly }}</td>
                        <td class="border-td text-center" style="font-size: 15pt; line-height: 32px">
                            {{ $totalChargeableWeight }}</td>
                        <td class="border-td" colspan="2"
                            style="font-size: 15pt; text-align: center;font-weight:bold; line-height: 32px">
                            <b>SUBTOTAL</b>
                        </td>
                        <td class="border-td" colspan="6"
                            style="font-size: 15pt; font-weight:bold;text-align: right; line-height: 32px">Rp.
                            {{ (new \App\Helpers\GeneralHelpers())->raw_currency($data->sub_total) }}</td>
                    </tr>
                    {{-- baris kosong --}}
                    <tr>
                        <td colspan="6">
                        </td>
                        <td></td>
                        <td class="border-td" colspan="2"
                            style="font-size: 15pt; text-align: center;font-weight:bold; line-height: 32px">PPN 1.1%
                        </td>
                        <td class="border-td" colspan="6"
                            style="font-size: 15pt; font-weight:bold;text-align: right; line-height: 32px">Rp.
                            {{ (new \App\Helpers\GeneralHelpers())->raw_currency($data->ppn) }}</td>
                    </tr>
                    <tr>
                        <td class="border-td" colspan="2" style="font-size: 15pt; line-height: 32px;width: 15%;">
                            INVOICE NO </td>
                        <td class="border-td" colspan="3" style="font-size: 15pt;">
                            <b>{{ $data->invoice_number }}</b>
                        </td>
                        <td></td>
                        <td></td>
                        <td class="border-td" colspan="2"
                            style="font-size: 15pt;text-align: center;font-weight:bold; line-height: 32px">DOWN PAYMENT
                        </td>
                        <td class="border-td" colspan="6"
                            style="font-size: 15pt;font-weight:bold;text-align: right; line-height: 32px">Rp.
                            {{ (new \App\Helpers\GeneralHelpers())->raw_currency($data->down_payment) }}</td>
                    </tr>
                    <tr>
                        <td class="border-td" colspan="2" style="font-size: 15pt;line-height: 32px;width: 15%;">
                            DUE DATE </td>
                        <td class="border-td" colspan="3" style="font-size: 15pt; line-height: 32px"><b>
                                @php
                                    if ($data->payment_type == 2) {
                                        echo (new \App\Helpers\GeneralHelpers())->date_format_inv(
                                            $data->payment_due_date,
                                        );
                                    }
                                @endphp
                            </b></td>
                        <td></td>
                        <td></td>
                        <td class="border-td" colspan="2"
                            style="font-size: 15pt;text-align: center;font-weight:bold; line-height: 32px">TOTAL</td>
                        <td class="border-td" colspan="6"
                            style="font-size: 15pt;font-weight:bold;text-align: right; line-height: 32px">Rp.
                            {{ (new \App\Helpers\GeneralHelpers())->raw_currency($data->total) }}</td>
                    </tr>
                    <tr>
                        <td class="border-td" colspan="2" style="font-size: 15pt;line-height: 32px;width: 15%;">
                            IDR</td>
                        <td class="border-td" colspan="3" style="font-size: 15pt; line-height: 32px">
                            <b>{{ (new \App\Helpers\GeneralHelpers())->raw_currency($data->total) }}</b>
                        </td>
                        <td colspan="8"></td>
                    </tr>
                    <tr>
                        <td colspan="13" style="line-height: 24px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="border-td" colspan="2" style="font-size: 15pt;line-height: 32px;width: 15%;">
                            Inword</td>
                        <td class="border-td" colspan="3" style="font-size: 15pt; line-height: 32px">
                            <b><i>{{ ucfirst((new \App\Helpers\GeneralHelpers())->formatTerbilang($data->total)) }}</i></b>
                        </td>
                        <td colspan="8"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="w-full col12 row">
            <div class="col6" style="width: 50%;font-size: 7pt;">
                <div class="note text-center" style="width: 50%;height: 50px;">
                    <p style="margin: auto;">Please Pay by cash or by transfer to the following :</p>
                    <p style="margin: auto;">An. DITA ROSLIA PUTRI </p>
                    @forelse ($banks as $bank)
                        <p style="margin: auto;">Account No {{ $bank->name_bank }} : {{ $bank->number_account }}</p>
                    @empty
                        <p style="margin: auto;">Account No MANDIRI : 155-00-1050517-3</p>
                        <p style="margin: auto;">Account No BCA : 2312-6489-80</p>
                    @endforelse

                </div>
            </div>
            <div class="text-center" style="width: 50%;font-size: 7pt;float: right">
                <div class="text-center" style="width: 50%;float: right; height: 50px;">
                    <p>APPROVED BY,</p><br><br><br><br><br><br>
                    (Dyta Ayudani Rosslia Putri)
                    <hr>
                    Finance Manager
                </div>
            </div>
        </div>
        <div style="width: 50%;font-size: 7pt;float: left;">
            <p style="margin-bottom: 0px;margin-top: 0px;">Payment Confirmation</p><br>
            <p style="margin-bottom: 0px;margin-top: 0px;">Phone : 021-22952468</p>
            <p style="margin-bottom: 0px;margin-top: 0px;">Mail : info@mascargoexpress.com/billing@mascargoexpress.com
            </p>
        </div>
    </div>
</body>

</html>
