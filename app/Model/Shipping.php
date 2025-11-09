<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    const IMAGE_PATH = 'shipping';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_type',
        'manifest_id',
        'type',
        'invoice_number',
        'invoice_date',
        'invoice_year',
        'sales_name',
        'shipping_name',
        'shipping_address',
        'shipping_city',
        'phone_number',
        'receipt_number',
        'destination',
        'moda',
        'ppn',
        'pph',
        'sub_total',
        'discount',
        'total',
        'remaining_payment',
        'down_payment',
        'payment_type',
        'payment_name',
        'payment_due_date',
        'payment_date',
        'payment_status',
        'debt_age',
        'include_pph',
        'is_verification',
        'verifier_name',
        'created_by',
        'deleted_at',
        'deleted_by',
        'json_inv_number',
        'result_last_number'
    ];

    protected $primaryKey = 'id';


    public function images(){
        return $this->hasMany(ShippingImage::class, 'shipping_id');
    }

    public function banks()
    {
        return $this->hasMany(ShippingBank::class, 'shipping_id');
    }

    public function vendor_invoice()
    {
        return $this->hasOne(VendorInvoice::class, 'shipping_id');
    }

    public function manifest()
    {
        return $this->belongsTo(Manifest::class, 'manifest_id');
    }
}
