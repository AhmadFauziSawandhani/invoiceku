<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VendorShipping extends Model
{
    protected $fillable = [
        'invoice_id',
        'shipping_id',
        'receipt_number',
        'shipping_name',
        'destination',
        'total',
    ];

    public function invoice()
    {
        return $this->belongsTo(VendorInvoice::class, 'invoice_id');
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }
}
