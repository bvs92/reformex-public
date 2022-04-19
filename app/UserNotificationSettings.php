<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserNotificationSettings extends Model
{
    protected $fillable = ['user_id', 'email', 'phone'];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // getters

    public function isEmailActive()
    {
        if ($this->email == true) {
            return true;
        } else {
            return false;
        }
    }

    public function isPhoneActive()
    {
        if ($this->phone == true) {
            return true;
        } else {
            return false;
        }
    }
}
