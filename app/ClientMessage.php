<?php

namespace App;

use App\User;
use App\Demand;
use App\Timeline;
use App\ClientMessage;
use App\ClientMessageFile;
use Illuminate\Database\Eloquent\Model;

class ClientMessage extends Model
{
    protected $fillable = ['user_id', 'timeline_id', 'demand_id', 'message'];




    // Relationships
    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    public function files()
    {
        return $this->hasMany(ClientMessageFile::class);
    }
}
