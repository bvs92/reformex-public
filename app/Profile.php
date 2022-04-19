<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['profile_photo', 'user_id'];





    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
