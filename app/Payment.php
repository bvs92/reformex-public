<?php

namespace App;

use App\Invoice;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'uuid', 'name', 'paymentable_id', 'paymentable_type', 'checkout_id', 'payment_status', 'amount_total', 'payment_intent'];

    // Relations

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function paymentable()
    {
        return $this->morphTo();
    }

    // Helpers

    public function getReadableAmount()
    {
        return $this->amount / 100;
    }

}
