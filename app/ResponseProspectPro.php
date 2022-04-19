<?php

namespace App;

use App\Prospect;
use Illuminate\Database\Eloquent\Model;

class ResponseProspectPro extends Model
{

    protected $fillable = ['prospect_id', 'user_id', 'response'];
    // Relationships

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }
}
