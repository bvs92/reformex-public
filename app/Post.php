<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'body'];

    public function path()
    {
        return route('posts.show', $this);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
