<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    protected $fillable = ['username', 'user_id', 'type'];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
