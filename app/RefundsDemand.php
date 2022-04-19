<?php

namespace App;

use App\User;
use App\Ticket;
use Illuminate\Database\Eloquent\Model;

class RefundsDemand extends Model
{
    protected $fillable = ['payment_intent_id', 'status', 'user_id', 'message', 'ticket_id'];





    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
