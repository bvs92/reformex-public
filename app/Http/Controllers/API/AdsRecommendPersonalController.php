<?php

namespace App\Http\Controllers\API;

use App\AdRecommendCompany;
use App\Http\Controllers\Controller;
use App\Notifications\RequestAdRecommendValidationNotification;
use App\Period;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdsRecommendPersonalController extends Controller
{
    public function activate(Request $request, $uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        if ($ad->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);

    }

    public function requestValidation(Request $request, $uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        if ($ad->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $ad->rejected = 0;
        $ad->editable = 0;
        $ad->processing = 1;
        $ad->save();

        // notify admins
        // notify user that the ad is active
        $admins = User::role('admin')->get();
        Notification::send($admins, new RequestAdRecommendValidationNotification($ad));

        return response()->json(['success' => true]);

    }

    public function calculate($uuid, $period_id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        if ($ad->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $period = Period::find($period_id);

        if (!$period) {
            return response()->json(['errors' => true]);
        }

        $price_per_day = 5;

        if ($ad->categories) {
            $ad_categories = $ad->categories;
        } else {
            return response()->json(['errors' => true]);
        }

        if ($ad_categories->count() > 0) {
            $total_categories = $ad_categories->count();
        } else {
            return response()->json(['errors' => true]);
        }

        $cost_categories = $total_categories * $price_per_day;
        // $total_cost = $cost_categories * $period->days;
        if ($total_categories >= 2) {
            $total_cost = $total_categories * intval($period->price);
            $total_cost = intval($total_cost / 1.1); // 10% discount
        } else if ($total_categories >= 4) {
            $total_cost = $total_categories * intval($period->price);
            $total_cost = intval($total_cost / 1.2); // 20% discount
        } else {
            $total_cost = $total_categories * intval($period->price);
        }

        return response()->json(['success' => true, 'cost' => $total_cost]);

    }

    public function personal()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ads = AdRecommendCompany::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();

        if ($ads->count() < 1) {
            return response()->json(['ads' => []]);
        }

        $ads = $ads->map(function ($item) {
            $item->categories;
            return $item;
        });

        return response()->json(['ads' => $ads]);
    }

    public function getSingleAd($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
            return response()->json(['ad' => null]);
        }

        if ($ad->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $ad->categories;

        if ($ad->messages) {
            $ad['rejectMessage'] = $ad->messages->latest()->first();
        }

        if ($ad->periods) {
            $ad['recent_period'] = $ad->periods()->orderBy('pivot_created_at', 'desc')->first();
        }

        // get all ad details

        return response()->json(['ad' => $ad]);
    }

    public function store(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'categories' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // categories
        $valid_request['categories'] = (explode(',', $valid_request['categories']));
        $final_categories = collect($valid_request['categories'])->map(function ($item) {
            return (int) $item;
        });

        if ($final_categories->count() < 1) {
            return response()->json(['errors' => true]);
        }

        // save record in DB
        DB::beginTransaction();

        $ad = new AdRecommendCompany();
        $ad->uuid = Str::uuid();
        $ad->user_id = $user->id;
        $ad->name = $valid_request['name'];
        $ad->phone = $valid_request['phone'];
        $ad->description = $valid_request['description'];
        $ad->type = 1; // proposed by user
        $ad->status = 0; // default inactive
        $ad->editable = 1; // default editable
        $ad->processing = 0; // in proces de analiza
        $ad->paid = 0; // ad is not paid
        $ad->rejected = 0; // initial not rejected.

        if ($request->location) {
            $ad->location = $valid_request['location'];
        }

        if ($request->cui) {
            $ad->cui = $valid_request['cui'];
        }

        // if ($request->email) {
        $ad->email = $valid_request['email'];
        // }

        if ($request->website) {
            $ad->website = $valid_request['website'];
        }

        if ($request->has_form) {
            $ad->has_form = $request->has_form == 'true' ? true : false;
        }

        if ($request->show_email) {
            $ad->show_email = $request->show_email == 'true' ? true : false;
        }

        if (!$ad->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        if (!$ad->categories()->sync(json_decode($final_categories))) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();

        return response()->json(['success' => true, 'ad_uuid' => $ad->uuid]);

    }

    public function delete($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        if ($ad->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        DB::beginTransaction();

        if (!$ad->delete()) {
            DB::rollback();
            return response()->json(['errors' => true]);
        }

        DB::commit();
        return response()->json(['success' => true]);
    }

    public function update_announce(Request $request, $id)
    {

        // return response()->json($request->has_form == 'true' ? 1 : 0);
        // return response()->json(intval($request->has_form));

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ad = AdRecommendCompany::find($id);

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        if ($ad->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'cui' => 'nullable',
            'location' => 'nullable',
            'website' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // save record in DB
        DB::beginTransaction();

        $ad->name = $valid_request['name'];
        $ad->phone = $valid_request['phone'];
        $ad->description = $valid_request['description'];

        $ad->location = $valid_request['location'];

        $ad->cui = $valid_request['cui'];

        // if ($request->email) {
        $ad->email = $valid_request['email'];
        // }

        $ad->website = $valid_request['website'];

        if (!$ad->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();
        return response()->json(['success' => true]);

    }

    public function update_announce_options(Request $request, $id)
    {

        // return response()->json($request->has_form == 'true' ? 1 : 0);
        // return response()->json(intval($request->has_form));

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ad = AdRecommendCompany::find($id);

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        if ($ad->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        // save record in DB
        DB::beginTransaction();

        if ($request->has_form) {
            $ad->has_form = $request->has_form == 'true' ? true : false;
        }

        if ($request->show_email) {
            $ad->show_email = $request->show_email == 'true' ? true : false;
        }

        if ($request->status) {
            $ad->status = $request->status == 'true' ? true : false;
        }

        if (!$ad->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();
        return response()->json(['success' => true]);

    }

    public function update_announce_period(Request $request, $id)
    {

        // return response()->json($request->has_form == 'true' ? 1 : 0);
        // return response()->json(intval($request->has_form));

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ad = AdRecommendCompany::find($id);

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        if ($ad->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $messages = [
            'period.in' => 'Perioada selectată nu este validă.',
        ];

        $validator = Validator::make($request->all(), [
            'period' => 'required|exists:periods,days',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // save record in DB
        DB::beginTransaction();

        //save period type
        $period = Period::where('days', $valid_request['period'])->first();

        if (!$period) {
            DB::rollBack();
            return response()->json(['errors' => true, 'period-1' => $period]);
        }

        try {
            $ad->periods()->attach($period->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => true, 'period' => $period]);
        }

        // daca ends_at > now, adauga perioada la ends_at
        // altfel, adauga perioada incepand de acum
        if ($ad->ends_at > now()) {
            // return response()->json(['rezultat' => 'mai mare ends at']);
            $ad->ends_at = Carbon::parse($ad->ends_at)->addDays(intval($valid_request['period']));
        } else {
            // return response()->json(['rezultat' => 'mai mic ends at']);
            $ad->ends_at = now()->addDays(intval($valid_request['period']));
        }

        if (!$ad->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();
        return response()->json(['success' => true, 'ends_at' => $ad->ends_at, 'recent_period' => $period]);

    }

    public function update_announce_categories(Request $request, $id)
    {

        // return response()->json($request->has_form == 'true' ? 1 : 0);
        // return response()->json(intval($request->has_form));

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $ad = AdRecommendCompany::find($id);

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        if ($ad->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // categories
        $valid_request['categories'] = (explode(',', $valid_request['categories']));
        $final_categories = collect($valid_request['categories'])->map(function ($item) {
            return (int) $item;
        });

        if ($final_categories->count() < 1) {
            return response()->json(['errors' => true]);
        }

        // save record in DB
        DB::beginTransaction();

        if (!$ad->categories()->sync(json_decode($final_categories))) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        // if (!$ad->save()) {
        //     DB::rollBack();
        //     return response()->json(['errors' => true]);
        // }

        DB::commit();
        return response()->json(['success' => true, 'categories' => $ad->categories]);

    }

}
