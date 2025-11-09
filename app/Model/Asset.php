<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asset_date',
        'turnover',
        'salary_account',
        'saving_account',
        'operational',
        'vendor',
        'religious_meal',
        'spending_amount'
    ];

    protected $primaryKey = 'id';
}
