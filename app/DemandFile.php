<?php

namespace App;

use App\Demand;
use App\User;
use Illuminate\Database\Eloquent\Model;

class DemandFile extends Model
{
    protected $fillable = ['demand_id', 'user_id', 'name', 'extension', 'path', 'mime_type'];

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
