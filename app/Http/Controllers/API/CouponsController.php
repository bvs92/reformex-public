<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\SendCouponToUserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CouponsController extends Controller
{

    // FOR ADMIN

    public function initialize()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $coupons = \App\Coupon::all();

        $coupons = $coupons->map(function ($item) {
            $item['user_id'] = $item->user ? $item->user->id : null;
            $item['email'] = $item->user ? $item->user->email : null;
            $item->makeHidden(['user']);
            $item->amount = $item->amount / 100;

            return $item;
        });

        return response()->json(['coupons' => $coupons, 'total' => $coupons->count()]);
    }

    public function initializePersonal()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $coupons = $user->coupons;

        $coupons = $coupons->map(function ($item) {
            $item['user_id'] = $item->user ? $item->user->id : null;
            $item['email'] = $item->user ? $item->user->email : null;
            $item->makeHidden(['user']);
            $item->amount = $item->amount / 100;

            return $item;
        });

        return response()->json(['coupons' => $coupons, 'total' => $coupons->count()]);
    }

    public function getCoupon($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $coupon = \App\Coupon::where('uuid', $uuid)->first();

        if (!$coupon) {
            return response()->json(['errors' => true]);
        }

        if ($coupon->user_id) {
            $coupon->user;
            $coupon->user->makeHidden(['card_brand', 'stripe_id', 'card_last_four']);
        }

        return response()->json(['coupon' => $coupon]);
    }

    public function getPersonalCoupon($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $coupon = $user->coupons()->where('uuid', $uuid)->first();

        if (!$coupon) {
            return response()->json(['errors' => true]);
        }

        if ($coupon->user_id) {
            $coupon->user;
            $coupon->user->makeHidden(['card_brand', 'stripe_id', 'card_last_four']);
        }

        return response()->json(['coupon' => $coupon]);
    }

    public function getUserCoupons($user_id)
    {
        try {
            $admin = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$admin->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $user = \App\User::find($user_id);

        if (!$user) {
            return response()->json(['errors' => true]);
        }

        $coupons = $user->coupons;

        $coupons = $coupons->map(function ($item) {
            $item['user_id'] = $item->user ? $item->user->id : null;
            $item['email'] = $item->user ? $item->user->email : null;
            $item->makeHidden(['user']);
            $item->amount = $item->amount / 100;

            return $item;
        });

        return response()->json(['coupons' => $coupons, 'total' => $coupons->count()]);
    }

    public function getUserActivatedCoupons($user_id)
    {
        try {
            $admin = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$admin->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $user = \App\User::find($user_id);

        if (!$user) {
            return response()->json(['errors' => true]);
        }

        $coupons = $user->coupons()->where('used', true)->get();

        $coupons = $coupons->map(function ($item) {
            $item['user_id'] = $item->user ? $item->user->id : null;
            $item['email'] = $item->user ? $item->user->email : null;
            $item->makeHidden(['user']);
            $item->amount = $item->amount / 100;

            return $item;
        });

        return response()->json(['coupons' => $coupons, 'total' => $coupons->count()]);
    }

    public function getUsers()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $pros = \App\Professional::all();

        $users = $pros->map(function ($item) {
            $item['user_id'] = $item->user ? $item->user->id : null;
            $item['email'] = $item->user ? $item->user->email : null;
            $item->makeHidden(['user', 'administrative', 'city', 'lat', 'lng', 'postal_code', 'range']);

            return $item;
        });

        return response()->json(['users' => $users]);
    }

    public function storeGenerate(Request $request)
    {
        // return response()->json(['request' => $request->all()]);
        // return response()->json(['user_id' => $request->user_id]);

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->valid();

        $coupon = new \App\Coupon();
        $coupon->uuid = $this->generateUUID();
        $coupon->amount = $validated_fields['amount'] * 100;
        // $coupon->code = strtoupper(explode('-', Str::uuid())[0]);
        $coupon->code = $this->generate_code();
        $coupon->user_id = null;

        // return response()->json(['validated_fields' => $validated_fields]);
        // return response()->json(['coupon' => $coupon]);

        if (!$coupon->save()) {
            return response()->json(['errors' => true]);
        }

        $coupon->amount = $coupon->amount / 100;

        return response()->json(['success' => true, 'coupon' => $coupon]);

    }

    public function storeForUser(Request $request)
    {
        // return response()->json(['notify' => $request->notify === 'true' ? true : false]);
        // return response()->json(['user' => \App\User::find($request->user_id)]);

        try {
            $admin = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$admin->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:20',
            'user_id' => 'required|exists:professionals,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->valid();

        $coupon = new \App\Coupon();
        $coupon->uuid = $this->generateUUID();
        $coupon->amount = $validated_fields['amount'] * 100;
        $coupon->code = $this->generate_code();
        $coupon->user_id = $validated_fields['user_id'];

        // return response()->json(['validated_fields' => $validated_fields]);
        // return response()->json(['$coupon' => $coupon]);

        if (!$coupon->save()) {
            return response()->json(['errors' => true]);
        }

        $coupon->amount = $coupon->amount / 100;

        $notify = $request->notify === 'true' ? true : false; // notify by email or not

        $user = \App\User::find($validated_fields['user_id']);
        Notification::send($user, new SendCouponToUserNotification($user, $coupon, $notify));

        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

    public function attachToUser(Request $request)
    {
        // return response()->json(['notify' => $request->notify === 'true' ? true : false]);
        // return response()->json(['user' => \App\User::find($request->user_id)]);

        try {
            $admin = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$admin->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:professionals,user_id',
            'coupon_id' => 'required|exists:coupons,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->valid();

        $coupon = \App\Coupon::find($validated_fields['coupon_id']);

        if ($coupon->used) {
            return response()->json(['errors' => true]);
        }

        // $coupon->used = true;
        $coupon->user_id = $validated_fields['user_id'];

        // return response()->json(['validated_fields' => $validated_fields]);
        // return response()->json(['$coupon->code' => $coupon->code]);

        if (!$coupon->save()) {
            return response()->json(['errors' => true]);
        }

        $notify = $request->notify === 'true' ? true : false; // notify by email or not

        $user = \App\User::find($validated_fields['user_id']);
        Notification::send($user, new SendCouponToUserNotification($user, $coupon, $notify));

        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

    public function delete($id)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $coupon = \App\Coupon::where('uuid', $id)->first();

        if (!$coupon) {
            return response()->json(['errors' => true]);
        }

        if (!$coupon->delete()) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function activate($id)
    {

        try {
            $admin = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$admin->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $coupon = \App\Coupon::where('uuid', $id)->first();

        if (!$coupon) {
            return response()->json(['errors' => true]);
        }

        if ($coupon->used) {
            return response()->json(['errors' => true]);
        }

        if (!$coupon->user_id) {
            return response()->json(['errors' => true]);
        }

        $coupon->used = true;
        $coupon->activated_at = now();

        // crediteaza credit din cupoane
        // begin transaction
        $user = $coupon->user;

        $user->credit->amount += $coupon->amount;

        DB::beginTransaction();

        if (!$user->credit->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        if (!$coupon->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        // DB::rollBack();
        DB::commit();

        // end transaction

        return response()->json(['success' => true]);
    }

    // FOR PRO

    public function activate_pro($id)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $coupon = $user->coupons()->where('uuid', $id)->first();

        if (!$coupon) {
            return response()->json(['errors' => true]);
        }

        if ($coupon->used) {
            return response()->json(['errors' => true]);
        }

        $coupon->used = true;
        $coupon->activated_at = now();

        // crediteaza credit din cupoane
        // begin transaction

        $user->credit->amount += $coupon->amount;

        DB::beginTransaction();

        if (!$user->credit->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        if (!$coupon->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        // DB::rollBack();
        DB::commit();
        // end transaction

        return response()->json(['success' => true]);
    }

    // helpers
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
