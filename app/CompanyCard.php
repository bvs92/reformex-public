<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyCard extends Model
{
    //fillable
    protected $fillable = ['company_id', 'image'];

    // relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // functions
    public function photo_exists()
    {
        $pathToFile = 'uploads/cards/' . $this->image;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {
            return true;
        } else {
            return false;
        }
    }
}
