<?php

namespace App;

use App\User;
use App\Ticket;
use Illuminate\Database\Eloquent\Model;

class TicketFile extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'name', 'extension', 'path', 'mime_type'];




    // Relationships

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
