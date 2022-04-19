<?php

namespace App;

use App\AdRecommendCompanyRejectMessage;
use App\Category;
use App\Payment;
use App\Period;
use App\User;
use Illuminate\Database\Eloquent\Model;

class AdRecommendCompany extends Model
{
    protected $fillable = ['uuid', 'user_id', 'name', 'cui', 'phone', 'email', 'location', 'website', 'description', 'starts_at', 'ends_at', 'status', 'has_form', 'show_email', 'type', 'processing', 'editable', 'paid', 'rejected'];

    // status: 0 inactiv, 1 activ
    // type: 0 - admin, 1 - proposed by user
    // processing: 0 = false, 1 = in curs de procesare

    // relationships
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(AdRecommendCompanyRejectMessage::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
}
