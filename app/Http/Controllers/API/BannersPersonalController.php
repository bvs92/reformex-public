<?php

namespace App\Http\Controllers\API;

use App\Banner;
use App\Http\Controllers\Controller;
use App\Notifications\RequestBannerValidationNotification;
use App\Period;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BannersPersonalController extends Controller
{

    public function activate(Request $request, $uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::where('uuid', $uuid)->first();

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);

    }

    public function requestValidation(Request $request, $uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::where('uuid', $uuid)->first();

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $banner->rejected = 0;
        $banner->editable = 0;
        $banner->processing = 1;
        $banner->save();

        // notify admins
        // notify user that the banner is active
        $admins = User::role('admin')->get();
        Notification::send($admins, new RequestBannerValidationNotification($banner));

        return response()->json(['success' => true]);

    }

    public function calculate($banner_uuid, $period_id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::where('uuid', $banner_uuid)->first();

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $period = Period::find($period_id);

        if (!$period) {
            return response()->json(['errors' => true]);
        }

        $price_per_day = 5;

        if ($banner->categories) {
            $banner_categories = $banner->categories;
        } else {
            return response()->json(['errors' => true]);
        }

        if ($banner_categories->count() > 0) {
            $total_categories = $banner_categories->count();
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
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banners = Banner::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();

        if ($banners->count() < 1) {
            return response()->json(['banners' => []]);
        }

        $banners = $banners->map(function ($item) {
            $item->categories;
            return $item;
        });

        return response()->json(['banners' => $banners]);
    }

    public function getSingleBanner($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::where('uuid', $uuid)->first();

        if (!$banner) {
            return response()->json(['banner' => null]);
        }

        if ($banner->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $banner->categories;

        if ($banner->messages) {
            $banner['rejectMessage'] = $banner->messages->latest()->first();
        }

        if ($banner->periods) {
            $banner['recent_period'] = $banner->periods()->orderBy('pivot_created_at', 'desc')->first();
        }

        // get all banner details

        return response()->json(['banner' => $banner]);
    }

    public function store(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$request->hasFile('photo')) {
            return response()->json(['errors' => true]);
        }

        $image_size = 100000 * 0.0009765625; // convert Kb in Mb

        $messages = [
            'max' => 'Imaginea trebuie sa fie de maxim ' . $image_size . ' Mb.',
        ];

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|max:100000',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'description' => 'required',
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

        // Image
        $file = $valid_request['photo'];
        $ext = $file->getClientOriginalExtension();
        $file_name = 'banner-photo-' . Str::uuid() . "-" . time() . '.' . $ext;

        $this->make_directory();

        // Save file

        // end image

        // save record in DB
        DB::beginTransaction();

        $banner = new Banner();
        $banner->uuid = Str::uuid();
        $banner->user_id = $user->id;
        $banner->image = $file_name;
        $banner->name = $valid_request['name'];
        $banner->phone = $valid_request['phone'];
        $banner->description = $valid_request['description'];
        $banner->type = 1; // proposed by user
        $banner->status = 0; // default inactive
        $banner->editable = 1; // default editable
        $banner->processing = 0; // in proces de analiza
        $banner->paid = 0; // banner is not paid
        $banner->rejected = 0; // initial not rejected.

        if ($request->location) {
            $banner->location = $valid_request['location'];
        }

        if ($request->cui) {
            $banner->cui = $valid_request['cui'];
        }

        // if ($request->email) {
        $banner->email = $valid_request['email'];
        // }

        if ($request->website) {
            $banner->website = $valid_request['website'];
        }

        if ($request->has_form) {
            $banner->has_form = $request->has_form == 'true' ? true : false;
        }

        if ($request->show_email) {
            $banner->show_email = $request->show_email == 'true' ? true : false;
        }

        if (!$banner->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        if (!$banner->categories()->sync(json_decode($final_categories))) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        try {
            // Image::make($file)
            //     ->resize(440, 480, function ($constraint) {
            //         $constraint->aspectRatio();
            //     })->save(storage_path('app/public') . '/banners/' . $file_name);

            $photo = Image::make($file)
                ->resize(440, 480, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode($ext, 80);

            $full_path = 'uploads/banners' . '/' . $file_name;

            Storage::disk('do_spaces')->put($full_path, $photo, 'public');

            // save record if image is saved.
            DB::commit();
        } catch (Exception $e) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true, 'banner_uuid' => $banner->uuid]);

    }

    public function delete($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::where('uuid', $uuid)->first();

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $image_name = $banner->image;

        DB::beginTransaction();

        if (!$banner->delete()) {
            DB::rollback();
            return response()->json(['errors' => true]);
        }

        if ($this->checkFileExists('banners', $image_name)) {
            $this->delete_existing_photo($image_name);
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
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
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

        $banner->name = $valid_request['name'];
        $banner->phone = $valid_request['phone'];
        $banner->description = $valid_request['description'];

        $banner->location = $valid_request['location'];

        $banner->cui = $valid_request['cui'];

        // if ($request->email) {
        $banner->email = $valid_request['email'];
        // }

        $banner->website = $valid_request['website'];

        if (!$banner->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();
        return response()->json(['success' => true]);

    }

    public function update_announce_image(Request $request, $id)
    {

        // return response()->json($request->has_form == 'true' ? 1 : 0);
        // return response()->json(intval($request->has_form));

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$request->hasFile('photo')) {
            return response()->json(['errors' => true]);
        }

        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $image_size = 100000 * 0.0009765625; // convert Kb in Mb

        $messages = [
            // 'min' => 'Imaginea trebuie sa fie de minim ' . $image_size . ' Mb.',
            'max' => 'Imaginea trebuie sa fie de maxim ' . $image_size . ' Mb.',
        ];

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|max:100000',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // Image
        $file = $valid_request['photo'];
        $ext = $file->getClientOriginalExtension();
        $file_name = 'banner-photo-' . Str::uuid() . "-" . time() . '.' . $ext;

        $this->make_directory();
        $this->delete_existing_photo($banner->image);

        // save record in DB
        DB::beginTransaction();

        $banner->image = $file_name;

        if (!$banner->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        try {
            // Image::make($file)
            //     ->resize(440, 480, function ($constraint) {
            //         $constraint->aspectRatio();
            //     })->save(storage_path('app/public') . '/banners/' . $file_name);

            $photo = Image::make($file)
                ->resize(440, 480, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode($ext, 80);

            $full_path = 'uploads/banners' . '/' . $file_name;

            Storage::disk('do_spaces')->put($full_path, $photo, 'public');

            // save record if image is saved.
            DB::commit();
        } catch (Exception $e) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true, 'image' => $file_name]);

    }

    public function update_announce_options(Request $request, $id)
    {

        // return response()->json($request->has_form == 'true' ? 1 : 0);
        // return response()->json(intval($request->has_form));

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        // save record in DB
        DB::beginTransaction();

        if ($request->has_form) {
            $banner->has_form = $request->has_form == 'true' ? true : false;
        }

        if ($request->show_email) {
            $banner->show_email = $request->show_email == 'true' ? true : false;
        }

        if ($request->status) {
            $banner->status = $request->status == 'true' ? true : false;
        }

        if (!$banner->save()) {
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
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
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
            $banner->periods()->attach($period->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => true, 'period' => $period]);
        }

        // daca ends_at > now, adauga perioada la ends_at
        // altfel, adauga perioada incepand de acum
        if ($banner->ends_at > now()) {
            // return response()->json(['rezultat' => 'mai mare ends at']);
            $banner->ends_at = Carbon::parse($banner->ends_at)->addDays(intval($valid_request['period']));
        } else {
            // return response()->json(['rezultat' => 'mai mic ends at']);
            $banner->ends_at = now()->addDays(intval($valid_request['period']));
        }

        if (!$banner->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();
        return response()->json(['success' => true, 'ends_at' => $banner->ends_at, 'recent_period' => $period]);

    }

    public function update_announce_categories(Request $request, $id)
    {

        // return response()->json($request->has_form == 'true' ? 1 : 0);
        // return response()->json(intval($request->has_form));

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
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

        if (!$banner->categories()->sync(json_decode($final_categories))) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        // if (!$banner->save()) {
        //     DB::rollBack();
        //     return response()->json(['errors' => true]);
        // }

        DB::commit();
        return response()->json(['success' => true, 'categories' => $banner->categories]);

    }

    private function make_directory()
    {

        if (!File::isDirectory(storage_path('app/public') . '/banners')) {
            File::makeDirectory(storage_path('app/public') . '/banners', 0777, true, true);
        }
    }

    private function delete_existing_photo($name)
    {
        // $pathToFile = storage_path('app/public') . '/banners/' . $name;
        // if (file_exists($pathToFile)) {
        //     unlink($pathToFile);
        // }
        $pathToFile = 'uploads/banners/' . $name;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {
            Storage::disk('do_spaces')->delete($pathToFile);
        }
    }

    public function checkFileExists($path, $name)
    {
        // $pathToFile = storage_path('app/public') . '/' . $path . '/' . $name;
        // if (file_exists($pathToFile)) {
        //     return true;
        // } else {
        //     return false;
        // }

        $pathToFile = 'uploads/banners/' . $name;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {
            return true;
        } else {
            return false;
        }
    }
}
