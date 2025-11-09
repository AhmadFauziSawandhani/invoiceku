<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OfficeSpending extends Model
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
        'spending_name',
        'spending_date',
        'amount',
        'spending_type',
        'created_by',
        'created_name'
    ];

    protected $primaryKey = 'id';


}
