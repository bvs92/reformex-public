<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['verified', 'user_id'];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
