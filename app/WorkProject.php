<?php

namespace App;

use App\ProjectCategory;
use App\User;
use App\WorkProjectPhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class WorkProject extends Model
{
    protected $fillable = ['uuid', 'user_id', 'title', 'description'];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(WorkProjectPhoto::class);
    }

    public function categories()
    {
        return $this->belongsToMany(ProjectCategory::class);
    }

    // Getters

    public function firstPhoto()
    {

        if ($this->photos() && $this->photos()->count()) {
            return $this->photos()->first() ? $this->photos()->first()->name : false;

        } else {
            return URL::asset('assets/images/media/8.jpg');
        }
    }

    public function getFirstPhoto()
    {
        if ($this->photos() && $this->photos()->count() > 0) {
            return $this->photos()->first() ? $this->photos()->first()->name : false;
        }

        return false;
    }

}
