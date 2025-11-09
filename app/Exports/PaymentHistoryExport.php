<?php

namespace App\Exports;

use App\Model\PaymentHistory;
use App\Model\Vendor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class PaymentHistoryExport implements FromView, WithProperties, WithStyles
{

    public function __construct($vendor_id, $dateStart, $dateEnd)
    {
        $this->vendor_id = $vendor_id;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }
    public function view(): View
    {
        $data = [];
        $dateStart = $this->dateStart;
        $dateEnd = $this->dateEnd;
        if($this->vendor_id){
            $vendor = Vendor::find($this->vendor_id);
            $query = PaymentHistory::where('vendor_id', $this->vendor_id);
            if ($this->dateStart && $this->dateEnd) {
                $query->whereBetween('date', [$this->dateStart, $this->dateEnd]);
            }
            $data = $query->orderBy('date', 'asc')->get();
            $totalInvoice = $data->where('type', 'invoice')->sum('amount');
            $totalPayment = $data->where('type', 'payment')->sum('amount');
            $totalSaldo =  $totalInvoice -  $totalPayment;
        }

        $payments = $data->toArray();

        for($index = 0; $index < count($payments); $index++){
            if($index == 0){
                $payments[$index]['saldo'] = $payments[$index]['amount'];
            }else{
                $payments[$index]['saldo'] = $payments[$index]['type'] == 'payment' ? $payments[$index - 1]['saldo'] - $payments[$index]['amount'] : $payments[$index - 1]['saldo'] + $payments[$index]['amount'];
            }

            if($payments[$index]['type'] == 'payment'){
                $payments[$index]['payment_amount'] = $payments[$index]['amount'];
                $payments[$index]['invoice_amount'] = 0;
            }else{
                $payments[$index]['payment_amount'] = 0;
                $payments[$index]['invoice_amount'] = $payments[$index]['amount'];
            }
        }

        return view('exports.payment-history',[
            'data' => $payments,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'vendor' => $vendor,
            'totalInvoice' => $totalInvoice,
            'totalPayment' => $totalPayment,
            'totalSaldo' => $totalSaldo
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => 'center'
                ]
            ],
            2 => [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => 'center'
                ]
            ],
            3 => [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => 'center'
                ]
            ],
            4 => [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => 'center'
                ]
            ],
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => config('app.name'),
            'lastModifiedBy' => config('app.name'),
            'title' => 'Data Laporan Pendapatan Penjahit',
            'description' => 'Data Laporan Pendapatan Penjahit',
        ];
    }
}
