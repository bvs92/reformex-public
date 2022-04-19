<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class CompanyQuestion extends Model
{
    protected $fillable = ['company_id', 'title', 'text'];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
