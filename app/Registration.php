<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = ['user_id', 'status', 'message'];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
