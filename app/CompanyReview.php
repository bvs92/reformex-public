<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CompanyReview extends Model
{
    protected $fillable = ['user_id', 'rating', 'message', 'status'];

    // status = 1 (acceptat)
    // status = 0 (dezactivat)

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
