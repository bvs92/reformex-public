<?php

namespace App;

use App\User;
use App\Demand;
use App\Timeline;
use App\QuoteFile;
use App\Professional;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    
    // protected $fillable = ['user_id','demand_id', 'price', 'message'];
    protected $fillable = ['timeline_id', 'professional_id','demand_id', 'price', 'message', 'user_id'];

    /** 
     * Relationships
     */
    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function files()
    {
        return $this->hasMany(QuoteFile::class);
    }

    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    



    // Getters

    public function showPublishDate()
    {
        if($this->created_at == NULL)
            return 'Nicio data setata.';
        else 
            return $this->created_at->diffForHumans() ?? 'Nesetat';
    }

    public function showProName()
    {
        return $this->professional->getName() ?? $this->professional->user->getName();
    }



}
