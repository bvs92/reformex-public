<?php

namespace App;

use App\Demand;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{

    /**
     * status: 0 = activ? 1 = completed?
     */
    protected $fillable = ['demand_id', 'status', 'offers'];


    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }
}
