<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
 */

Broadcast::channel('tickets-channel', function () {
    // who will get the notification when a new ticket is created by an user?
    if (auth()->user()->hasRole('admin|editor|moderator')) {
        return true;
    }

});

Broadcast::channel('tickets-actions-channel', function () {
    // who will get the notification when a new ticket is created by an user?
    if (auth()->user()->hasRole('admin|editor|moderator')) {
        return true;
    }

});

Broadcast::channel('demands-channel.{demand_id}', function ($user, $demand_id) {
    $demand = \App\Demand::where('id', $demand_id)->first();
    if ($user->id === $demand->user_id) {
        return true;
    }

});

Broadcast::channel('client-timelines-channel.{timeline_id}', function ($user, $timeline_id) {
    $timeline = \App\Timeline::where('id', $timeline_id)->first();
    if ($user->id === $timeline->professional->user_id) {
        return true;
    }

});

Broadcast::channel('pro-timelines-channel.{timeline_id}', function ($user, $timeline_id) {
    $timeline = \App\Timeline::where('id', $timeline_id)->first();
    if ($user->id === $timeline->user_id) {
        return true;
    }

});

Broadcast::channel('tickets-channel.{ticket_id}', function ($user, $ticket_id) {
    $ticket = \App\Ticket::where('id', $ticket_id)->first();
    if ($user->id === $ticket->user_id) {
        return true;
    } else if ($user->hasRole('admin|moderator|editor')) {
        if ($ticket->resolvers()->where('user_id', $user->id)->count() > 0) {
            return true;
        }
    }

});

Broadcast::channel('user-tickets-messages-channel.{ticket_id}', function ($user, $ticket_id) {
    $ticket = \App\Ticket::where('id', $ticket_id)->first();
    if ($user->id === $ticket->user_id) {
        return true;
    } else if ($user->hasRole('admin|moderator|editor')) {
        $resolver = $ticket->resolvers()->where('user_id', $user->id)->first();
        if ($resolver->count() > 0) {
            if ($resolver->subscribed == true) {
                return true;
            }

        }
    }

});

Broadcast::channel('pro-quotes-channel.{quote_id}', function ($user, $quote_id) {
    $quote = \App\Quote::where('id', $quote_id)->first();
    if ($user->id === $quote->user_id) {
        return true;
    }

});

// Broadcast::channel('demands-channel', function () {
//         return true;
// });
