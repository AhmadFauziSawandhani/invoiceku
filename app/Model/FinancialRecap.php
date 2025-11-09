<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FinancialRecap extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'global_turnover',
        'turnover',
        'vendor',
        'saving',
        'salary',
        'operational'
    ];

    protected $primaryKey = 'id';
}
