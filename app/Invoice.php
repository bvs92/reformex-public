<?php

namespace App;

use App\Payment;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['user_id', 'payment_id', 'name', 'extension', 'mime_type'];

    // relationships
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
