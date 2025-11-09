<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'account_bank',
        'account_name',
        'account_number',
    ];

    protected $appends = [
        'saldo',
        'total_invoice',
        'total_payment',
    ];

    public function invoices()
    {
        return $this->hasMany(VendorInvoice::class);
    }

    public function accounts(){
        return $this->hasMany(VendorAccount::class);
    }

    public function payments()
    {
        return $this->hasMany(PaymentHistory::class)->where('type', PaymentHistory::TYPE_PAYMENT);
    }

    public function getSaldoAttribute()
    {
        return $this->invoices()->sum('amount') - $this->payments()->sum('amount');
    }

    public function getTotalInvoiceAttribute()
    {
        return $this->invoices()->sum('amount');
    }

    public function getTotalPaymentAttribute()
    {
        return $this->payments()->sum('amount');
    }
}
