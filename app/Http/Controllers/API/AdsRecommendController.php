<?php

namespace App\Http\Controllers\API;

use App\Banner;
use App\Period;
use Carbon\Carbon;
use App\AdRecommendCompany;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AdRecommendCompanyRejectMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendDirectMessageToBanner;
use App\Notifications\AdRecommendActivationNotification;
use App\Notifications\SendDirectMessageToAdRecommendNotification;

class AdsRecommendController extends Controller
{
    public function index()
    {
        $ads = AdRecommendCompany::where('ends_at', '>=', now())->where('status', 1)->get();

        if ($ads->count() < 1) {
            return response()->json(['ads' => []]);
        }

        return response()->json(['ads' => $ads]);
    }

    public function getByCategory($slug)
    {
        $ads = AdRecommendCompany::whereHas('categories', function (Builder $query) use ($slug) {
            $query->where('slug', '=', $slug);
        })->where('ends_at', '>=', now())->where('status', 1)->get();

        if ($ads->count() < 1) {
            return response()->json(['ads' => []]);
        }

        return response()->json(['ads' => $ads]);
    }

    public function load($type)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        if ($type == 'active') {
            $ads = AdRecommendCompany::where('ends_at', '>=', now())->where('status', 1)->get();
        } else if ($type == 'inactive') {
            $ads = AdRecommendCompany::where('status', 0)->get();
        } else if ($type == 'expired') {
            $ads = AdRecommendCompany::where('ends_at', '<', now())->get();
        }

        if ($ads->count() < 1) {
            return response()->json(['ads' => []]);
        }

        // $ads = $ads->map(function($item){
        //     $item->user;
        //     return $item;
        // });

        return response()->json(['ads' => $ads]);
    }

    public function loadProcessing()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $ads = AdRecommendCompany::where('processing', 1)->get();

        if ($ads->count() < 1) {
            return response()->json(['ads' => []]);
        }

        return response()->json(['ads' => $ads]);
    }

    public function getSingleAd($uuid)
    {
        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
            return response()->json(['ad' => null]);
        }

        $ad->categories;
        $ad->payments;

        if ($ad->payments) {
            if ($ad->payments->count() > 0) {
                $ad->payments = $ad->payments->map(function ($item) {
                    $item->invoice;
                    return $item;
                });
            }
        }

        if ($ad->messages && $ad->messages->count() > 0) {
            $ad['rejectMessage'] = $ad->messages()->orderBy('created_at', 'desc')->first();
        }

        if ($ad->periods) {
            $ad['recent_period'] = $ad->periods()->orderBy('pivot_created_at', 'desc')->first();
        }

        // get all ad details

        return response()->json(['ad' => $ad]);
    }

    public function activate($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        $period = $ad->periods()->orderBy('pivot_created_at', 'desc')->first();
        // return response()->json(['period' => $period]);

        $ad->starts_at = now();
        $ad->ends_at = now()->addDays(intval($period->days));
        $ad->status = 1;
        $ad->processing = 0;
        $ad->editable = 0;
        $ad->rejected = 0;

        if ($ad->messages) {
            $ad->messages()->delete();
        }

        if (!$ad->save()) {
            return response()->json(['errors' => true]);
        }

        // notify user that the ad is active
        if ($ad->user) {
            Notification::send($ad->user, new AdRecommendActivationNotification($ad));
        }

        return response()->json(['success' => true]);
    }

    public function reject(Request $request, $uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
            return response()->json(['errors' => true]);
        }

        DB::beginTransaction();
        $ad->status = 0;
        $ad->processing = 1;
        $ad->editable = 1;
        $ad->rejected = 1;

        if (!$ad->save()) {
            DB::rollback();
            return response()->json(['errors' => true]);
        }

        if ($request->message) {
            $message = new AdRecommendCompanyRejectMessage();
            $message->ad_recommend_company_id = $ad->id;
            $message->message = $request->message;

            if (!$message->save()) {
                DB::rollback();
                return response()->json(['errors' => true]);
            }
        }

        DB::commit();
        // notify user that the ad is active
        if ($ad->user) {
            Notification::send($ad->user, new AdRecommendActivationNotification($ad));
        }

        return response()->json(['success' => true]);
    }

    public function getPublicSingleAd($uuid)
    {
        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
            return response()->json(['ad' => null]);
        }

        $ad->categories;

        // if ($ad->periods) {
        //     $ad['recent_period'] = $ad->periods()->orderBy('pivot_created_at', 'desc')->first();
        // }

        // get all ad details

        return response()->json(['ad' => $ad]);
    }

    public function store(Request $request)
    {

        // return response()->json($request->has_form == 'true' ? 1 : 0);
        // return response()->json('aici');

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $messages = [
            // 'min' => 'Imaginea trebuie sa fie de minim ' . $image_size . ' Mb.',
            'period.in' => 'Perioada selectată nu este validă.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'period' => 'required|exists:periods,days',
            'categories' => 'required|exists:categories,id',
        ], $messages);

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

        // Save file

        // end image

        // save record in DB
        DB::beginTransaction();

        $ad = new AdRecommendCompany();
        $ad->uuid = Str::uuid();
        $ad->name = $valid_request['name'];
        $ad->phone = $valid_request['phone'];
        $ad->description = $valid_request['description'];

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

        if ($request->status) {
            $ad->status = $request->status == 'true' ? true : false;
        }

        $ad->editable = 0;

        $ad->starts_at = now();
        $ad->ends_at = now()->addDays(intval($valid_request['period']));

        //save period type
        $period = Period::where('days', $valid_request['period'])->first();

        if (!$ad->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        if (!$period) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        try {
            $ad->periods()->attach($period->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => true, 'period' => $period]);
        }

        if (!$ad->categories()->sync(json_decode($final_categories))) {
            DB::rollBack();
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

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $ad = AdRecommendCompany::find($id);

        if (!$ad) {
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

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $ad = AdRecommendCompany::find($id);

        if (!$ad) {
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

            if ($request->status == 'true') {
                $ad->editable = 0;
            } else {
                $ad->editable = 1;
            }
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

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $ad = AdRecommendCompany::find($id);

        if (!$ad) {
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

        $ad->status = 1;
        $ad->editable = 0;
        $ad->paid = 1;
        $ad->rejected = 0;
        $ad->processing = 0;

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

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $ad = AdRecommendCompany::find($id);

        if (!$ad) {
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

    public function delete($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $ad = AdRecommendCompany::where('uuid', $uuid)->first();

        if (!$ad) {
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

    public function sendFormMessage(Request $request)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'type' => 'required',
            'id' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()], 422);
        }

        $validated_form = $validator->valid();


        if($validated_form['type'] == 'ad'){
            $ad = AdRecommendCompany::find($validated_form['id']);
    
            if (!$ad) {
                return response()->json(['errors' => true], 500);
            }
    
            Notification::route('mail', $ad->email)->notify(new SendDirectMessageToAdRecommendNotification($validated_form));
        } else {
            $banner = Banner::find($validated_form['id']);

            if (!$banner) {
                return response()->json(['errors' => true], 500);
            }

            Notification::route('mail', $banner->email)->notify(new SendDirectMessageToBanner($validated_form));
        }

        return response()->json(['success' => true]);
    }
}
