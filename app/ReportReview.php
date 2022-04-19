<?php

namespace App;

use App\Review;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ReportReview extends Model
{
    // status: 0 = in curs, 1 = valid, 2 = invalid
    protected $fillable = ['user_id', 'description', 'status'];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
