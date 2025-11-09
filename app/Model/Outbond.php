<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outbond extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function manifest()
    {
        return $this->hasMany(Manifest::class, 'outbond_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
