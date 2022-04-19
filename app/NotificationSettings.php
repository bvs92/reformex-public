<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class NotificationSettings extends Model
{
    protected $fillable = ['user_id', 'daily_reminder', 'each_demand', 'promotion'];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
