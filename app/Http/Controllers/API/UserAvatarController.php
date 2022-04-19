<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserAvatarController extends Controller
{
    public function get()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->profile) {
            return response()->json(['errors' => true]);
        }

        if (!$user->profile->profile_photo) {
            return response()->json(['errors' => true]);
        }

        // return response()->json(['success' => true, 'avatar' => $user->getFullProfilePhoto()]);
        return response()->json(['success' => true, 'avatar' => $user->getFullThumbnailProfilePhoto()]);
    }

    public function delete()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->profile) {
            return response()->json(['errors' => true]);
        }

        $this->delete_existing_avatar($user->profile);

        if (!$user->profile()->delete()) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        // return response()->json(['request' => $request->all()]);

        if (!$request->hasFile('photo')) {
            return response()->json(['errors' => true]);
        }

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|max:100000000',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        if (!$user->profile) {
            $profile = new \App\Profile();
            $profile->user_id = $user->id;
        } else {
            $profile = $user->profile;
            $this->delete_existing_avatar($profile);
        }

        $file = $valid_request['photo'];
        $ext = $file->getClientOriginalExtension();
        $file_name = 'profil-' . Str::uuid() . "-" . auth()->user()->id . '.' . $ext;

        $this->make_directory();

        // Save file

        // Image::make($file)
        //     ->resize(800, 800, function ($constraint) {
        //         $constraint->aspectRatio();
        //     })
        //     ->save(storage_path('app/public') . '/avatars/' . $file_name);

        $photo = Image::make($file)
            ->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
            })->encode($ext, 80);

        $full_path = 'uploads/avatars/' . $file_name;

        Storage::disk('do_spaces')->put($full_path, $photo, 'public');

        // Thumbnail image
        // Image::make($file)
        //     ->resize(300, 300, function ($constraint) {
        //         $constraint->aspectRatio();
        //     })
        //     ->save(storage_path('app/public') . '/avatars/thumbnails/' . $file_name);

        $profile->profile_photo = $file_name;

        if (!$profile->save()) {
            return response()->json(['errors' => true]);
        }

        // return response()->json(['success' => true, 'avatar' => auth()->user()->getFullProfilePhoto()]);
        return response()->json(['success' => true, 'avatar' => auth()->user()->getFullThumbnailProfilePhoto()]);
    }

    private function make_directory()
    {

        if (!File::isDirectory(storage_path('app/public') . '/avatars')) {
            File::makeDirectory(storage_path('app/public') . '/avatars', 0777, true, true);
        }

        if (!File::isDirectory(storage_path('app/public') . '/avatars/thumbnails')) {
            File::makeDirectory(storage_path('app/public') . '/avatars/thumbnails', 0777, true, true);
        }

        // if (!File::isDirectory(public_path('images/avatars'))) {
        //     File::makeDirectory(public_path('images/avatars'), 0777, true, true);
        // }

        // if (!File::isDirectory(public_path('images/avatars/thumbnails'))) {
        //     File::makeDirectory(public_path('images/avatars/thumbnails'), 0777, true, true);
        // }
    }

    public function delete_existing_avatar($current_profile)
    {
        if ($current_profile->profile_photo && $current_profile->profile_photo != 'default-photo.png') {

            $pathToFile = 'uploads/avatars/' . $current_profile->profile_photo;
            if (Storage::disk('do_spaces')->exists($pathToFile)) {
                Storage::disk('do_spaces')->delete($pathToFile);
            }

            // $pathToFile = storage_path('app/public') . '/avatars/' . $current_profile->profile_photo;
            // if (file_exists($pathToFile)) {
            //     unlink($pathToFile);
            // }

            // $pathToThumbnail = storage_path('app/public') . '/avatars/thumbnails/' . $current_profile->profile_photo;
            // if (file_exists($pathToThumbnail)) {
            //     unlink($pathToThumbnail);
            // }

        }
    }
}
