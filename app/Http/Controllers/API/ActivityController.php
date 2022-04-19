<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function getUserActivities($user_id)
    {
        if (!auth()->user()->isAdmin()) {
            return \response()->json(['errors' => true]);
        }

        $user = \App\User::find($user_id);

        if (!$user) {
            return \response()->json(['errors' => true]);
        }

        $activities = $user->activities->map(function ($item) {
            $item['demand_uuid'] = $item->demand ? $item->demand->uuid : 'Eliminata';
            $item->makeHidden(['demand']);
            return $item;
        });

        return response()->json(['activities' => $activities, 'total_activities' => $activities->count()]);
    }

    public function getPersonalActivities()
    {
        $user = auth()->user();

        if (!$user) {
            return \response()->json(['errors' => true]);
        }

        if (!$user->isPro()) {
            return \response()->json(['errors' => true]);
        }

        $activities = $user->activities()->latest()->get()->map(function ($item) {
            $item['demand_uuid'] = $item->demand ? $item->demand->uuid : 'Eliminata';
            $item->makeHidden(['demand']);
            return $item;
        });

        return response()->json(['activities' => $activities, 'total_activities' => $activities->count()]);
    }
}
