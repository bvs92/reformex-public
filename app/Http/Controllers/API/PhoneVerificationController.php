<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\VerifyDemandNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class PhoneVerificationController extends Controller
{
    public function send(Request $request)
    {
        // return response()->json(['phone' => $request->phone]);

        // validate phone number

        $code = rand(1000, 9999);

        $demand_verification = new \App\PhoneVerification;
        $demand_verification->uuid = Str::uuid();
        $demand_verification->code = $code;
        if (!$demand_verification->save()) {
            return response()->json(['errors' => true]);
        }

        Notification::route('nexmo', $request->phone)->notify(new VerifyDemandNotification($code));

        return response()->json(['request_uuid' => $demand_verification->uuid, 'success' => true]);
    }

    public function verify(Request $request)
    {
        // return response()->json(['code' => $request->all()]);
        $element = \App\PhoneVerification::where('uuid', $request->uuid)->first();

        if ($element) {
            if ($element->code == $request->code) {
                $element->status = true;
                $element->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['errors' => true]);
            }
        } else {
            return response()->json(['errors' => true]);
        }
    }

    public function delete(Request $request, $uuid)
    {

        $phone_verification = \App\PhoneVerification::where('uuid', $uuid)->first();

        $phone_verification->delete();
        return response()->json(['success' => true]);
    }
}
