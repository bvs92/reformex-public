<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class MessagesController extends Controller
{

    public function totalUnreadMessages()
    {

        try {
            $user = auth()->userOrFail();
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $total_unread_messages_tickets = 0;

        if ($user->hasRole('admin|editor|moderator')) {
            $ids = $user->resolvers()->where('subscribed', true)->pluck('ticket_id');
            // preia doar tickets subscribed?
            $tickets = \App\Ticket::whereIn('id', $ids)->get();
            $arr_tickets = $tickets->pluck('id');
        } else {
            // 1. Get all unread messages from tickets
            $tickets = $user->tickets;
            $arr_tickets = $user->tickets()->pluck('id');
        }

        // $total_unread_messages_tickets = \App\ResponseTicket::where('user_id', '!=', $user->id)->whereIn('ticket_id', $arr_tickets)->where('read', false)->count();
        $total_unread_messages_tickets = \App\ResponseTicketInformation::where('user_id', auth()->user()->id)->where('read_at', null)->count();

        return response()->json([
            'unread_messages' => $total_unread_messages_tickets,
        ]);

    }

}
