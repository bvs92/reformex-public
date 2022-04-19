<?php

namespace App;

use App\User;
use App\ResponseTicket;
use Illuminate\Database\Eloquent\Model;

class ResponseFile extends Model
{
    protected $fillable = ['response_ticket_id', 'user_id', 'name', 'extension', 'path', 'mime_type'];




    // Relationships

    public function responseTicket()
    {
        return $this->belongsTo(ResponseTicket::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
