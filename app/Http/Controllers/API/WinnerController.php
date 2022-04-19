<?php

namespace App\Http\Controllers\API;

use App\Demand;
use App\Http\Controllers\Controller;
use App\Notifications\TimelineAction;
use App\Winner;
use Illuminate\Support\Facades\Notification;

class WinnerController extends Controller
{

    public function confirm_winner($timeline_uuid)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $timeline_uuid)->first();

        if (!$timeline) {
            return response()->json(['error' => 'The was an error. The resource does not exists.']);
        }

        if (!$user->can('update_client', $timeline)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        // check if already exists a winner for this demand. de eliminat?
        if ($timeline->demand->hasActiveWinner()) {
            return response()->json(['error' => 'A winner already exists.']);
        }

        $winner = new Winner();
        $winner->demand_id = $timeline->demand->id;
        $winner->user_id = $user->id;
        $winner->professional_id = $timeline->professional->id;
        $winner->status = '1';

        if (!$winner->save()) {
            return response()->json(['error', 'Am intampinat erori. Incercati mai tarziu.']);
        }

        // desactivate demand if is active
        if ($timeline->demand->state == 1) {
            $timeline->demand->state = 0;
            $timeline->demand->save();
        }

        if ($timeline->demand->isActive()) {
            $timeline->demand->detail->status = 1; // mark completed
            $timeline->demand->detail->save();
        }

        // notify other participants
        Notification::send($winner->professional->user, new TimelineAction($timeline, $timeline->user, 'confirm_winner'));

        // send notifications to losers of the demand.
        $losers = $timeline->demand->professionals()->where('user_id', '!=', $winner->professional->user->id)->get();
        if ($losers->count() > 0) {
            // get the user object from pro object
            $users_losers = $losers->map(function ($item) {
                return $item->user;
            });

            foreach ($users_losers as $loser) {
                $the_timeline = \App\Timeline::where('demand_id', $timeline->demand_id)->where('professional_id', $loser->professional->id)->first();
                Notification::send($loser, new TimelineAction($the_timeline, $timeline->user, 'refuse_winner'));
                event(new \App\Events\WinnerDemandEvent($the_timeline, $user, $winner));
            }

        }
        // real time event send for the winner
        event(new \App\Events\WinnerDemandEvent($timeline, $user, $winner));

        return response()->json(['winner' => $winner]);
    }

    public function cancel_winner($timeline_uuid)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $timeline_uuid)->first();

        if (!$timeline) {
            return response()->json(['error' => 'The was an error. The resource does not exists.']);
        }

        if (!$user->can('update_client', $timeline)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        try {
            $winners = \App\Winner::where('demand_id', $timeline->demand_id)->where('status', '!=', '2')->update(['status' => 2]); // update(['status' => 2])
            $prospects = \App\Prospect::where('timeline_id', $timeline->id)->where('status', '1')->update(['status' => 4]); // update(['status' => 4]). 4 = refuzat de owner cerere

            Notification::send($timeline->professional->user, new TimelineAction($timeline, $timeline->user, 'cancel_winner'));
            event(new \App\Events\TimelineCancelWinner($timeline, $user));

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['response' => 'success', 'winners' => $winners, 'prospects' => $prospects]);
    }

    public function winners_change($demand_uuid, $pro_id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json($user);

        $demand = Demand::where('uuid', $demand_uuid)->first();

        if (!$demand) {
            return response()->json(['errors' => 'Nu am gasit nimic pentru id trimis.']);
        }

        if (!$user->can('update', $demand)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        if (!$demand->isBought($pro_id)) {
            return response()->json(['errors' => 'Eroare. Profesionistul nu exista.']);
        }

        // $winners = $demand->hasWinner() ? $demand->getAllWinners() : null;

        // return response()->json(['pro' => $pro_id, '$demand-pro' => $demand->id]);

        if (!$demand->hasWinners()) {
            return response()->json(['errors' => 'Eroare. Nu am gasit niciun castigator.']);
        }

        $winner = $demand->hasActiveWinner() ? $demand->getWinner() : null;

        if (!$winner) {
            return response()->json(['errors' => 'Eroare. Nu am gasit niciun castigator activ.']);
        }

        // return response()->json('trecut');

        $timelines = $demand->timelines()->where('professional_id', '!=', $pro_id)->get();

        try {

            // cancel existing winners, if more than 1 (for safety)
            $result_update_winners = \App\Winner::where('demand_id', $demand->id)->where('status', '!=', '2')->update(['status' => 2]); // update(['status' => 2])

            // iterate through timelines and send Cancel Notifications + Event to Losers
            if ($timelines->count() > 0) {
                foreach ($timelines as $timeline) {
                    Notification::send($timeline->professional->user, new TimelineAction($timeline, $timeline->user, 'cancel_winner'));
                    event(new \App\Events\TimelineCancelWinner($timeline, $user));
                }
            }

            $new_winner = new Winner();
            $new_winner->demand_id = $demand->id;
            $new_winner->user_id = $user->id;
            $new_winner->professional_id = $pro_id;
            $new_winner->status = '1';

            if (!$new_winner->save()) {
                return response()->json(['error', 'Am intampinat erori. Incercati mai tarziu.']);
            }

            // send notification + event for new winner
            $winner_timeline = $demand->timelines()->where('professional_id', $pro_id)->first();
            Notification::send($new_winner->professional->user, new TimelineAction($winner_timeline, $winner_timeline->user, 'confirm_winner'));
            event(new \App\Events\WinnerDemandEvent($winner_timeline, $user, $new_winner));
            if ($winner_timeline->isCompleted()) {
                $winner_timeline->status = '0';
                $winner_timeline->save();
            }

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['winner' => $new_winner]);

    }

    protected function create_new_winner()
    {
        $new_winner = new Winner();
        $new_winner->demand_id = $demand->id;
        $new_winner->user_id = $user->id;
        $new_winner->professional_id = $pro_id;
        $new_winner->status = '1';

        return $new_winner;
    }

}
