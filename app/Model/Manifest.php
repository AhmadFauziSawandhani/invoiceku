<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manifest extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function product()
    {
        return $this->hasMany(ManifestProduct::class);
    }
    public function details()
    {
        return $this->hasMany(ManifestProduct::class);
    }

    public function shipping()
    {
        return $this->hasOne(ShippingDetail::class, 'manifest_id');
    }

    public function creaedUser()
    {
        return $this->belongsTo(User::class,'created_by','uuid');
    }

    public function tracking()
    {
        return $this->hasMany(ManifestTracking::class, 'manifest_id', 'id');
    }

    public function lastTracking()
    {
        return $this->hasOne(ManifestTracking::class, 'manifest_id', 'id')->latest();
    }
}
