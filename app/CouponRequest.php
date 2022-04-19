<?php

namespace App;

use App\Coupon;
use App\User;
use Illuminate\Database\Eloquent\Model;

class CouponRequest extends Model
{
    protected $fillable = ['user_id', 'status', 'coupon_id'];
    // status = 0 (refuzat), status = 1 (acceptat), null = in curs
    // coupon_id nullable, = id coupon when accepted

    // relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
