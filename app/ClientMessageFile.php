<?php

namespace App;

use App\User;
use App\ClientMessage;
use Illuminate\Database\Eloquent\Model;

class ClientMessageFile extends Model
{
    protected $fillable = ['client_message_id', 'user_id', 'name', 'extension', 'path', 'mime_type'];




    // Relationships

    public function clientMessage()
    {
        return $this->belongsTo(ClientMessage::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
