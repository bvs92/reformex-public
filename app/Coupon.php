<?php

namespace App;

use App\CouponRequest;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'amount', 'used', 'user_id', 'activated_at', 'uuid'];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function request()
    {
        return $this->hasOne(CouponRequest::class);
    }

}
