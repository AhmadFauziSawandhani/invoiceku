<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $guarded = [];

    public function manifest()
    {
        return $this->belongsTo(Manifest::class, 'receipt_number', 'receipt_number');
    }
}
