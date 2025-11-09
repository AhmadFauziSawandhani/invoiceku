<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VendorInvoice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_number',
        'vendor_id',
        'vendor_name',
        'date',
        'due_date',
        'amount',
        'remaining_amount',
        'remark',
        'created_by',
        'created_name',
        'shipping_id',
        'no_resi',
        'nama_cs',
        'tujuan',
        'omset',
        'hpp',
        'profit'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function payment_history()
    {
        return $this->hasOne(PaymentHistory::class, 'ref_id', 'id')->where('type', PaymentHistory::TYPE_INVOICE);
    }

    public function payments()
    {
        return $this->hasMany(PaymentHistory::class, 'invoice_id', 'id')->where('type', PaymentHistory::TYPE_PAYMENT);
    }

    public function shippings()
    {
        return $this->hasMany(VendorShipping::class, 'invoice_id', 'id');
    }
}
