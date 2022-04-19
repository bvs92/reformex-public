<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Judet extends Model
{
    protected $fillable = ['id', 'code', 'name', 'name_simple', 'slug'];

    // relationships
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}
