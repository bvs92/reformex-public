<?php

namespace App;

use App\ResponseFile;
use App\ResponseTicketInformation;
use App\Ticket;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ResponseTicket extends Model
{
    protected $fillable = ['user_id', 'ticket_id', 'message', 'filename', 'read'];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function responseFiles()
    {
        return $this->hasMany(ResponseFile::class);
    }

    public function information()
    {
        return $this->hasMany(ResponseTicketInformation::class);
    }
}
