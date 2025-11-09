<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_type',
        'foreign_key',
        'action',
        'prior_update',
        'after_update',
        'update_by',
        'update_name'
    ];

    protected $primaryKey = 'id';
}
