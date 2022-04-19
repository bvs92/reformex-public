<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'status', 'type'];
    // status: 0 = inactiv
    // status: 1 = activ

    // type: albastru, verde, galben

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
