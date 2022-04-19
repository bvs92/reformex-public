<?php

namespace App;

use App\AdRecommendCompany;
use App\Banner;
use App\Demand;
use App\Professional;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['uuid', 'slug', 'name', 'price', 'description'];

    // Relationships
    public function demands()
    {
        return $this->belongsToMany(Demand::class)->withTimestamps();
    }

    public function professionals()
    {
        return $this->belongsToMany(Professional::class)->withTimestamps();
    }

    public function banners()
    {
        return $this->belongsToMany(Banner::class)->withTimestamps();
    }

    public function ads_recommend_companies()
    {
        return $this->belongsToMany(AdRecommendCompany::class)->withTimestamps();
    }

    // Getters
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getPriceNormal()
    {
        return $this->price / 100;
    }

    public function getPriceInUnits()
    {
        return $this->price;
    }

}
