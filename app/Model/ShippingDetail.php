<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShippingDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipping_id',
        'manifest_id',
        'receipt_number',
        'moda',
        'destination',
        'starting_price',
        'price',
        'colly',
        'chargeable_weight',
        'unit',
        'product',
        'price_addons_packing',
        'price_addons_pickup',
        'price_addons_insurance',
        'minimum_hdl',
        'shipdex',
        'dus_un',
        'acc_xray',
        'adm_smu',
        'forklift',
        'lalamove_grab',
        'remarks',
        'amount',
    ];

    public function manifest(){
        return $this->belongsTo(Manifest::class, 'manifest_id');
    }

    public function shipping(){
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }
}
