<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    const TYPE_INVOICE = 'invoice';
    const TYPE_PAYMENT = 'payment';

    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
