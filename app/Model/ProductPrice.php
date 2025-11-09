<?php

namespace App\MOdel;

use App\Model\Product;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $fillable = [ 
        'price', 
        'product_id',
        'effective_date'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
