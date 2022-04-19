<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class CompanyLocation extends Model
{
    protected $fillable = ['company_id', 'value', 'lat', 'lng', 'details'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
