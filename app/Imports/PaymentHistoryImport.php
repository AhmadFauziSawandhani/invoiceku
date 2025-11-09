<?php

namespace App\Imports;

use App\Model\PaymentHistory;
use App\Model\Vendor;
use App\Model\VendorInvoice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PaymentHistoryImport implements ToCollection, WithHeadingRow
{

    protected $vendor_id;
    public function __construct($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $vendor = Vendor::find($this->vendor_id);

        if($vendor){
            foreach ($collection as $row)
            {
                if($row['idr_invoice'])
                {
                    $invoice = VendorInvoice::create([
                        'invoice_number' => $row['invoice_no'],
                        'vendor_id' => $this->vendor_id,
                        'vendor_name' => $vendor->name,
                        'date' => Date::excelToDateTimeObject($row['tanggal']),
                        'amount' => $row['idr_invoice'],
                        'remark' => $row['remark'],
                        'due_date' => $row['jatuh_tempo'] ? Date::excelToDateTimeObject($row['jatuh_tempo']) : null,
                        'created_by' => Auth::user()->id,
                        'created_name' => Auth::user()->full_name
                    ]);

                    $payment = PaymentHistory::create([
                        'vendor_id' => $this->vendor_id,
                        'ref_id' => $invoice->id,
                        'date' => Date::excelToDateTimeObject($row['tanggal']),
                        'due_date' => $row['jatuh_tempo'] ? Date::excelToDateTimeObject($row['jatuh_tempo']) : null,
                        'invoice_no' => $row['invoice_no'],
                        'amount' => $row['idr_invoice'],
                        'type' => PaymentHistory::TYPE_INVOICE,
                        'remark' => $row['remark'],
                    ]);
                }else{

                    $payment = PaymentHistory::create([
                        'vendor_id' => $this->vendor_id,
                        'date' => Date::excelToDateTimeObject($row['tanggal']),
                        'invoice_no' => $row['invoice_no'],
                        'amount' => $row['idr_payment'],
                        'type' => PaymentHistory::TYPE_PAYMENT,
                        'remark' => $row['remark'],
                    ]);
                }
            }
        }
    }
}
