<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationSettingsController extends Controller
{

    public function index()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$user->global_notification_settings) {
            $settings = new \App\NotificationSettings;
            $settings->user_id = $user->id;
            $settings->daily_reminder = true;
            $settings->each_demand = true;
            $settings->promotion = true;

            if (!$settings->save()) {
                return response()->json(['error' => true]);
            }

            return response()->json(['settings' => $settings]);
        }

        return response()->json(['settings' => $user->global_notification_settings]);
    }

    public function toggle(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $settings = $user->global_notification_settings;

        if ($request->type == 'daily_reminder') {
            $settings->daily_reminder = !$settings->daily_reminder;
        } else if ($request->type == 'each_demand') {
            $settings->each_demand = !$settings->each_demand;
        } else if ($request->type == 'promotion') {
            $settings->promotion = !$settings->promotion;
        } else {
            return response()->json(['error' => true]);
        }

        if (!$settings->save()) {
            return response()->json(['error' => true]);
        }

        return response()->json(['success' => true]);
    }
}
