<?php

namespace App;

use App\Demand;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $fillable = ['demand_id', 'user_id', 'amount_paid'];

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
