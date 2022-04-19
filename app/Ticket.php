<?php

namespace App;

use App\RefundsDemand;
use App\ResponseTicket;
use App\TicketAction;
use App\TicketFile;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['user_id', 'uuid', 'subject', 'message', 'priority', 'status', 'department_id'];

    // department_id: 0 = General, 2 = Tehnic, 1 = Comercial

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(ResponseTicket::class);
    }

    public function files()
    {
        return $this->hasMany(TicketFile::class);
    }

    public function refund_demand()
    {
        return $this->hasOne(RefundsDemand::class);
    }

    public function resolvers()
    {
        return $this->hasMany(\App\TicketResolver::class);
    }

    public function ticket_actions()
    {
        return $this->hasMany(TicketAction::class);
    }

    // Getters

    public function hasUUID()
    {
        return $this->uuid != null ? true : false;
    }

    public function getUUID()
    {
        return $this->uuid;
    }

    public function getDisponibleId()
    {
        return $this->hasUUID() ? $this->getUUID() : $this->id;
    }

    public function isMine()
    {
        return $this->user_id == auth()->user()->id ? true : false;
    }

    public function hasResolvers()
    {
        if ($this->resolvers && $this->resolvers()->count() > 0) {
            return true;
        }

        return false;
    }

    public function resolverIsMe()
    {
        $resolvers = $this->resolvers()->where('user_id', auth()->user()->id)->get();
        if ($resolvers->count() > 0) {
            return true;
        }

        return false;
    }

    public function isSubscribed()
    {
        $resolver = $this->resolvers()->where('user_id', auth()->user()->id)->first();

        return $resolver->subscribed == true ? true : false;
    }

    public function getResolvers()
    {
        return $this->resolvers;
    }
}
