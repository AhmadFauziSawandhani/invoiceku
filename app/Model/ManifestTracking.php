<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManifestTracking extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function manifest()
    {
        return $this->belongsTo(Manifest::class);
    }
}
