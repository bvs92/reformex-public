<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localitate extends Model
{
    protected $fillable = ['id', 'nume', 'diacritice', 'judet', 'auto', 'zip', 'populatie', 'lat', 'lng'];
}
