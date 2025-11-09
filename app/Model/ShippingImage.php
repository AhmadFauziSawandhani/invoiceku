<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShippingImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipping_id',
        'image',
        'created_by',
        'created_name'
    ];

    protected $primaryKey = 'id';
}
