<?php

namespace App;

use App\User;
use App\Demand;
use Illuminate\Database\Eloquent\Model;

class DemandReport extends Model
{
    /**
     * Professional users can report fake demands.
     *  */ 



    protected $fillable = ['user_id', 'demand_id', 'message', 'status'];


    // user_id = id of USER (Professional)
    // status: 0 = in curs, 1 = terminat (cererea este falsa), 2 = cerere valida.

    // Relationships

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
