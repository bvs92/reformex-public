<?php

namespace App;

use App\User;
use App\Demand;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['user_id', 'demand_id', 'description', 'amount'];




    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }
}
