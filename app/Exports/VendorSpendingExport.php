<?php

namespace App\Exports;

use App\Model\VendorSpending;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class VendorSpendingExport implements FromCollection, WithHeadings, WithColumnWidths, WithEvents
{
    public function __construct($request)
    {
        $this->dateStart = $request['dateStart'];
        $this->dateEnd = $request['dateEnd'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data =  VendorSpending::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'invoice_number',
            'vendor_name',
            DB::raw("DATE_FORMAT(`spending_date`, '%d-%m-%Y' ) as spending_date"),
            'amount',
            'spending_type'
        ]);

        if($this->dateStart)
        {
            $data = $data->where('spending_date', '>=', $this->dateStart);
        }

        if($this->dateEnd)
        {
            $data = $data->where('spending_date', '<=', $this->dateEnd);
        }

        return $data->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Invoice',
            'Nama Vendor',
            'Tanggal Pengeluaran',
            'Jumlah',
            'Jenis Pengeluaran'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20
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
                $cellRange = 'A1:F1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);
                $event->sheet->getStyle($cellRange)->ApplyFromArray($styleArray);
                $event->sheet->getStyle($cellRange)->applyFromArray($styleHeader);
                $event->sheet->getStyle('A2:A'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('B2:B'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('C2:C'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('D2:D'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('E2:E'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getStyle('F2:F'.$event->sheet->getHighestRow())->applyFromArray($styleBody);
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
