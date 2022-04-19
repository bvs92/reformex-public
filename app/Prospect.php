<?php

namespace App;

use App\ResponseProspectPro;
use App\Timeline;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    # Status:
    #   0: Hold
    #   1: Acceptata de PRO
    #   2: Refuzata de PRO
    # ------------ for vue just first 3
    #   3: Confirmat de client
    #   4: Refuzat de client

    protected $fillable = ['demand_id', 'user_id', 'professional_id', 'timeline_id', 'status'];

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    public function pro_response()
    {
        return $this->hasOne(ResponseProspectPro::class);
    }

    // Helpers

    public function getStatus()
    {
        return $this->status;
    }

    public function isOnHold()
    {
        return $this->status == '0' ? true : false;
    }

    public function isAccepted()
    {
        return $this->status == '1' ? true : false;
    }

    public function isRefused()
    {
        return $this->status == '2' ? true : false;
    }

    public function isConfirmedByClient()
    {
        return $this->status == '3' ? true : false;
    }

    public function isRefusedByClient()
    {
        return $this->status == '4' ? true : false;
    }

}
