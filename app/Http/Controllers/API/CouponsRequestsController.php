<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\RequestCouponNotification;
use App\Notifications\ResponseCouponRequestNotification;
use App\Notifications\SendCouponToUserNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CouponsRequestsController extends Controller
{

    // admin
    public function initialize_all(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $type = $request->input('type');

        if ($type == 'pending') {
            $requests = \App\CouponRequest::where('status', null)->get();
        } else if ($type == 'accepted') {
            $requests = \App\CouponRequest::where('status', 1)->get();
        } else if ($type == 'refused') {
            $requests = \App\CouponRequest::where('status', 0)->get();
        } else {
            $requests = \App\CouponRequest::all();
        }

        $requests = $requests->map(function ($item) {
            $item['email'] = $item->user->email;
            $item->makeHidden(['user']);
            $item->coupon;
            return $item;
        });

        return response()->json(['requests' => $requests, 'total' => $requests->count()]);
    }

    // pro

    public function initialize_personal()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $requests = $user->coupons_requests;

        $requests = $requests->map(function ($item) {
            $item->coupon;
            return $item;
        });

        return response()->json(['requests' => $requests, 'total' => $requests->count()]);
    }

    public function get_pending_requests()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $requests = $user->coupons_requests()->where('status', null)->get();

        return response()->json(['requests' => $requests, 'total' => $requests->count()]);
    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $request = new \App\CouponRequest;
        $request->user_id = $user->id;
        if (!$request->save()) {
            return response()->json(['errors' => true]);
        }

        $admins = User::role('admin')->get();

        Notification::send($admins, new RequestCouponNotification($user));

        return response()->json(['success' => true]);
    }

    public function accept(Request $request, $id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        // validare valoare cupon

        $validator = Validator::make($request->all(), [
            'coupon' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->valid();

        // return response()->json(['coupon' => $validated_fields]);

        $request_coupon = \App\CouponRequest::find($id);

        if (!$request_coupon) {
            return response()->json(['errors' => true]);
        }

        DB::beginTransaction();

        // create coupon

        $coupon = new \App\Coupon();
        $coupon->uuid = $this->generateUUID();
        $coupon->amount = intval($validated_fields['coupon']) * 100;
        // $coupon->code = strtoupper(explode('-', Str::uuid())[0]);
        $coupon->code = $this->generate_code();
        $coupon->user_id = $request_coupon->user_id;

        if (!$coupon->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        // atasare coupon_id la request_coupon.

        $request_coupon->status = 1;
        $request_coupon->coupon_id = $coupon->id;

        if (!$request_coupon->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }
        DB::commit();

        // send notification
        $receiver = \App\User::find($coupon->user_id);
        Notification::send($receiver, new SendCouponToUserNotification($receiver, $coupon, true));

        return response()->json(['success' => true]);
    }

    public function refuse(Request $request, $id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $request_coupon = \App\CouponRequest::find($id);

        if (!$request_coupon) {
            return response()->json(['errors' => true]);
        }

        $request_coupon->status = 0;

        if (!$request_coupon->save()) {
            return response()->json(['errors' => true]);
        }

        // send notification
        $receiver = \App\User::find($request_coupon->user_id);
        $type = 'refuse';
        Notification::send($receiver, new ResponseCouponRequestNotification($receiver, $type, true));

        return response()->json(['success' => true]);
    }

    private function generate_code()
    {
        $code = strtoupper(explode('-', Str::uuid())[0]);

        $existing = \App\Coupon::where('code', $code)->first();

        while ($existing) {
            $code = strtoupper(explode('-', Str::uuid())[0]);
            $existing = \App\Coupon::where('code', $code)->first();
        }

        return $code;
    }

    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        $id = substr($res, 0, 8);

        // verifica daca exista in db
        while (\App\Coupon::where('uuid', $id)->get()->count() > 0) {
            // regenereaza daca exista
            $res = \Illuminate\Support\Str::uuid();

            $id = substr($res, 0, 8);
        }

        return $id;
    }
}
