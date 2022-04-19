<?php

namespace App\Http\Controllers\API;

use App\Events\ProResponseForClientProposalEvent;
use App\Events\TimelineProposalClientToProEvent;
use App\Http\Controllers\Controller;
use App\Notifications\TimelineAction;
use App\Prospect;
use App\ResponseProspectPro;
use Illuminate\Support\Facades\Notification;

class ProspectsController extends Controller
{

    public function get($timeline_uuid)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $timeline_uuid)->first();

        if (!$user->can('update_client', $timeline)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        if (!$timeline) {
            return response()->json([
                'error' => 'Unable to continue.',
            ]);
        }

        $prospect_accepted = $timeline->prospects()->where('status', '1')->first();
        $prospect_refused = $timeline->prospects()->where('status', '2')->first();

        return response()->json([
            'prospects' => $timeline->prospects,
            'prospects_on_hold' => $timeline->prospects()->where('status', '0')->count(),
            'prospect_accepted' => $prospect_accepted,
            'prospect_refused' => $prospect_refused,
        ]);
    }

    public function send($timeline_uuid)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $timeline_uuid)->first();

        if (!$user->can('update_client', $timeline)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        if (!$timeline) {
            return response()->json([
                'error' => 'Unable to continue.',
            ]);
        }

        // if exists at least 1 prospect on hold, return
        $prospects_on_hold = $timeline->prospects()->where('status', '0')->count();
        if ($prospects_on_hold > 0) {
            return response()->json([
                'error' => 'Actiunea nu poate fi executata.',
            ]);
        }

        // if exists at least 1 prospect accepted by PRO, return
        $prospects_accepted = $timeline->prospects()->where('status', '1')->count();
        if ($prospects_accepted > 0) {
            return response()->json([
                'error' => 'Actiunea nu poate fi executata. Exista deja o propunere acceptata.',
            ]);
        }

        $prospect = new Prospect();
        $prospect->demand_id = $timeline->demand_id;
        $prospect->professional_id = $timeline->professional_id;
        $prospect->user_id = $user->id;
        $prospect->timeline_id = $timeline->id;

        if (!$prospect->save()) {
            return response()->json(['error' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        // Notify PRO
        Notification::send($timeline->professional->user, new TimelineAction($timeline, $timeline->user, 'proposition'));
        event(new \App\Events\ClientProposalEvent($timeline, $user));
        event(new TimelineProposalClientToProEvent($timeline)); // send proposal.

        return response()->json(['success' => 'Actiune executata cu succes.']);
    }

    public function accept_proposal_pro($prospect_id)
    { // only users that are PRO can use this.

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json($user);

        $prospect = Prospect::find($prospect_id);

        // check if USER is PRO
        if (!$user->isPro()) {
            return response()->json(['error' => 'Actiunea nu a putut fi executata.']);
        }

        // check if USER is authorized to modify prospect record.
        if (!$user->can('update_pro', $prospect)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        // already exist a response for this prospect.
        if ($prospect->pro_response) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        $response = ResponseProspectPro::create([
            'prospect_id' => $prospect->id,
            'user_id' => $user->id, // professional user id
            'response' => true,
        ]);

        if (!$response) {
            return response()->json(['error' => 'Actiunea nu a putut fi executata.']);
        }

        $prospect->status = '1';
        $prospect->save();

        Notification::send($prospect->timeline->user, new TimelineAction($prospect->timeline, $prospect->timeline->professional->user, 'accept'));
        event(new \App\Events\ProResponseProposalEvent($prospect->timeline, auth()->user(), 'accept'));
        // send event
        event(new ProResponseForClientProposalEvent($prospect->timeline, $prospect));

        return response()->json(['response' => $response]);
    }

    public function refuse_proposal_pro($prospect_id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json($user);

        $prospect = Prospect::find($prospect_id);

        // check if USER is PRO
        if (!$user->isPro()) {
            return response()->json(['error' => 'Actiunea nu a putut fi executata.']);
        }

        // check if USER is authorized to modify prospect record.
        if (!$user->can('update_pro', $prospect)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        // already exist a response for this prospect.
        if ($prospect->pro_response) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        $response = ResponseProspectPro::create([
            'prospect_id' => $prospect->id,
            'user_id' => $user->id, // professional user id
            'response' => false,
        ]);

        if (!$response) {
            return response()->json(['error' => 'Actiunea nu a putut fi executata.']);
        }

        $prospect->status = '2';
        $prospect->save();

        Notification::send($prospect->timeline->user, new TimelineAction($prospect->timeline, $prospect->timeline->professional->user, 'refuse'));
        event(new \App\Events\ProResponseProposalEvent($prospect->timeline, auth()->user(), 'refuse'));
        // send event & notification
        event(new ProResponseForClientProposalEvent($prospect->timeline, $prospect));

        return response()->json(['response' => $response]);
    }
}
