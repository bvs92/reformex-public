<?php

namespace App;

use App\Demand;
use App\Professional;
use App\ReportReview;
use App\Timeline;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'professional_id', 'demand_id', 'timeline_id', 'rating', 'message', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    public function report()
    {
        return $this->hasOne(ReportReview::class);
    }

    // getters
    public function getName()
    {
        return $this->name;
    }

    public function isReported()
    {
        return $this->report ? true : false;
    }
}
