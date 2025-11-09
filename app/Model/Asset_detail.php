<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Asset_detail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asset_id',
        'asset_date',
        'transaction_id',
        'transaction_type',
        'invoice_number',
        'amount',
        'vendor_payment',
        'operational_payment',
        'salary_payment',
        'saving_payment',
        'payment_name',
        'spending_type',
        'source'
    ];
}
