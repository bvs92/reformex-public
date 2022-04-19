<?php

namespace App;

use App\User;
use App\Professional;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = ['user_id', 'amount'];



    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function substractAmount($amount)
    {
        return $this->amount -= $amount;
    }
}
