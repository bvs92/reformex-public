<?php

namespace App;

use App\Demand;
use App\Professional;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $fillable = ['demand_id', 'user_id', 'professional_id', 'status'];

    /**
     * Status:
     *  - 0: on hold;
     *  - 1: confirmed by client;
     *  - 2: refused by client;
     */

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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

}
