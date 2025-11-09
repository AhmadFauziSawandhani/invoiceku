<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VendorSpending extends Model
{
    const TYPE_OPERATIONAL = 'Oprasional';
    const TYPE_TURNOVER = 'Omset';
    const TYPE_VENDOR = 'Vendor';
    const TYPE_SALARY = 'Gaji';
    const TYPE_SAVING = 'Tabungan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_number',
        'invoice_id',
        'vendor_id',
        'vendor_name',
        'amount',
        'spending_type',
        'spending_date',
        'created_by',
        'created_name',
        'remark',
        'account_name',
        'account_number',
        'account_bank',
    ];

    protected $primaryKey = 'id';


    public function payment_history()
    {
        return $this->hasOne(PaymentHistory::class,'ref_id','id')->where('type', PaymentHistory::TYPE_PAYMENT);
    }
}
