<?php

namespace App;

use App\AdRecommendCompany;
use Illuminate\Database\Eloquent\Model;

class AdRecommendCompanyRejectMessage extends Model
{
    protected $fillable = ['ad_recommend_company_id', 'message'];

    // relationships
    public function ad()
    {
        return $this->belongsTo(AdRecommendCompany::class);
    }
}
