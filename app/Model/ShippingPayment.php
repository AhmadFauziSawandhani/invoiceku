<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShippingPayment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipping_id',
        'verification_type',
        'total_pay',
        'nominal',
        'bank_name',
        'created_by',
        'created_name'
    ];

    protected $primaryKey = 'id';
}
