<?php

namespace App;

use App\User;
use App\Demand;
use Illuminate\Database\Eloquent\Model;

class DemandAttachment extends Model
{
    protected $fillable = ['demand_id', 'user_id', 'name', 'initial_name', 'extension', 'path', 'mime_type'];

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
