<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['code', 'name'];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
