<?php

namespace App;

use App\Banner;
use Illuminate\Database\Eloquent\Model;

class BannerRejectMessage extends Model
{
    protected $fillable = ['banner_id', 'message'];

    // relationships
    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}
