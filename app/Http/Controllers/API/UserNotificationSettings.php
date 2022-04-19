<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserNotificationSettings extends Controller
{

    public function getNotificationSettings()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->notification_settings) {
            return response()->json(['missing' => true]);
        }

        return response()->json(['exists' => true, 'notifications' => $user->notification_settings]);
    }

    public function activateNotifications()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->notification_settings) {
            if (!auth()->user()->notification_settings()->create()) {
                return response()->json(['errors' => 'Am intampinat erori.']);
            }
        }

        return response()->json(['success' => true, 'notifications' => auth()->user()->notification_settings]);
    }

    public function updateNotifications(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->notification_settings) {
            return response()->json(['errors' => 'Am intampinat erori.']);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        $user->notification_settings->email = (bool) $valid_request['email'];
        $user->notification_settings->phone = (bool) $valid_request['phone'];

        if (!$user->notification_settings->save()) {
            return response()->json(['errors' => 'Am intampinat erori.']);
        }

        return response()->json(['success' => true, 'notifications' => $user->notification_settings]);
    }
}
