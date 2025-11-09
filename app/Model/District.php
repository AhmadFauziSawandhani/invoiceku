<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }
}
