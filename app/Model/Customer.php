<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'ref_id')->where('ref_type', 'customers');
    }
}
