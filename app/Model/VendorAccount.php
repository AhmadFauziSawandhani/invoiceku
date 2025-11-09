<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VendorAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id',
        'account_name',
        'account_number',
        'account_bank',
    ];
}
