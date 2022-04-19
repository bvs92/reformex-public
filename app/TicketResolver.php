<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketResolver extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'subscribed'];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(\App\Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
