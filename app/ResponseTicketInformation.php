<?php

namespace App;

use App\ResponseTicket;
use App\Ticket;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ResponseTicketInformation extends Model
{
    // nu este folosit
    protected $fillable = ['user_id', 'ticket_id', 'response_ticket_id', 'read_at'];

    // relationships

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function response()
    {
        return $this->belongsTo(ResponseTicket::class);
    }
}
