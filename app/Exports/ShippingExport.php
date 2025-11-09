<?php

namespace App\Exports;

use App\Model\Shipping;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ShippingExport implements FromQuery, WithHeadings, WithColumnWidths, WithEvents
{
    public function __construct($request)
    {
        $this->dateStart = $request['dateStart'];
        $this->dateEnd = $request['dateEnd'];
        $this->filterBy = $request['filterBy'];
        $this->shipperPhoneNumber = $request['shipperPhoneNumber'];
        $this->sales_name = $request['sales_name'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data = Shipping::orderBy('invoice_date', 'asc');
        if($this->dateStart)
        {
            $data = $data->where('invoice_date', '>=', $this->dateStart);
        }
        if($this->dateEnd)
        {
            $data = $data->where('invoice_date', '<=', $this->dateEnd);
        }
        if($this->sales_name)
        {
            $data = $data->where('sales_name', $this->sales_name);
        }
        if($this->filterBy)
        {
            // dd($this->filterBy);
            if ($this->filterBy == 1 || $this->filterBy == 2) {
                $data = $data->where('payment_type', $this->filterBy);
            }

            if ($this->filterBy == 3) {
                $data = $data->where('is_verification', 1);
            }

            if ($this->filterBy == 4) {
                $dueDate = Carbon::today()->subDays(3)->toDateString();
                $data = $data->where('payment_status', 1)->where('payment_due_date', '>', $dueDate);
            }

            if ($this->filterBy == 5) {
                $data = $data->where('invoice_type', 1);
            }

            if ($this->filterBy == 6) {
                $data = $data->where('invoice_type', 3);
            }

            if ($this->filterBy == 7) {
                $data = $data->where('invoice_type', 2);
            }
        }
        $data = $data->select(
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('(CASE
                WHEN invoice_type = 1 THEN "LAUT"
                WHEN invoice_type = 2 THEN "KENDARAAN"
                ELSE "UDARA"
                END) AS invoice_type'),
            'invoice_number',
            DB::raw("DATE_FORMAT(`invoice_date`, '%d-%m-%Y' ) as invoice_date"),
            'shipping_name',
            'sales_name',
            'shippings.receipt_number',
            'shippings.moda',
            'shippings.destination',
            'price',
            'colly',
            'chargeable_weight',
            'unit',
            'product',
            'price_addons_packing',
            'price_addons_insurance',
            'price_addons_pickup',
            'minimum_hdl',
            'shipdex',
            'dus_un',
            'acc_xray',
            'adm_smu',
            'remarks',
            'amount',
            'ppn',
            'sub_total',
            'total',
            DB::raw('(CASE
                WHEN payment_type = 1 THEN "CASH"
                ELSE "TOP"
                END) AS payment_type'),
            DB::raw('(CASE
                WHEN payment_status = 1 THEN "Belum Lunas"
                ELSE "Lunas"
                END) AS payment_status')
        )->join('shipping_details', 'shipping_id', 'shippings.id');

        return $data;

    }

    public function headings(): array
    {
        return [
            'No',
            'Jenis Invoice',
            'Nomor Invoice',
            'Tanggal Innvoice',
            'Nama Pengirim',
            'Nama Marketing',
            'Nomor Resi',
            'Moda',
            'Destinasi',
            'Tarif',
            'Colly',
            'Berat Kiloan (Kg)',
            'Unit',
            'Produk',
            'Tambahan Packing',
            'Tambahan Asuransi',
            'Tambahan Pickup',
            'Minimum HDL',
            'Shipdex',
            'Dus un',
            'Acc Xray',
            'Adm smu',
            'Remarks',
            'Amount',
            'PPN',
            'Sub Total',
            'TOTAL',
            'Jenis Bayar',
            'Status'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 12,
            'C' => 30,
            'D' => 15,
            'E' => 25,
            'F' => 13,
            'G' => 13,
            'H' => 15,
            'I' => 25,
            'J' => 20,
            'K' => 10,
            'M' => 15,
            'N' => 15,
            'O' => 17,
            'P' => 17,
            'Q' => 17,
            'R' => 17,
            'S' => 17,
            'T' => 17,
            'U' => 17,
            'V' => 17,
            'W' => 17,
            'X' => 22,
            'Y' => 15,
            'Z' => 20,
            'AA' => 22,
            'AB' => 10,
            'AC' => 12,
        ];
    }

    public function registerEvents(): array
    {
        $styleArray = [
            'font' => [
                'bold' => true,
            ]
        ];

        $styleHeader = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000']
                ],
            ]
        ];

        $styleBody = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000']
                ],
            ]
        ];

        return [
            AfterSheet::class => function(AfterSheet $event) use ($styleArray, $styleHeader, $styleBody)
            {
                $cellRange = 'A1:AC1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);
                $event->sheet->getStyle($cellRange)->ApplyFromArray($styleArray);
                $event->sheet->getStyle($cellRange)->applyFromArray($styleHeader);
                $event->sheet->getStyle('A2:A'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('B2:B'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('C2:C'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('D2:D'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('E2:E'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('F2:F'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('G2:G'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('H2:H'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('I2:I'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('J2:J'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('K2:K'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('L2:L'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('M2:M'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('N2:N'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('O2:O'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('P2:P'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('Q2:Q'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('R2:R'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('S2:S'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('T2:T'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('U2:U'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('V2:V'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('W2:W'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('X2:X'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('Y2:Y'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('Z2:Z'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('AA2:AA'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('AB2:AB'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('AC2:AC'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
