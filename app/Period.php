<?php

namespace App;

use App\AdRecommendCompany;
use App\Banner;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = ['days', 'price', 'visible'];
    // visible - folosit pentru a ascunde de client

    public function banners()
    {
        return $this->belongsToMany(Banner::class)->withTimestamps();
    }

    public function ads_recommend_companies()
    {
        return $this->belongsToMany(AdRecommendCompany::class)->withTimestamps();
    }
}
