<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CompanyCardController extends Controller
{

    public function getCard()
    {
        $user = auth()->user();
        // if not login, error
        if (!$user) {
            return response()->json(['errors' => true]);
        }

        $company = \App\Company::where('user_id', $user->id)->first();

        if (!$company) {
            return response()->json(['errors' => true]);
        }

        if (!$company->card) {
            return response()->json(['card' => null]);
        }

        $card = $company->card;

        return response()->json(['card' => $card]);
    }

    public function update(Request $request)
    {

        if (!$request->hasFile('image')) {
            return response()->json(['errors' => true]);
        }

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        // if not login, error
        if (!$user) {
            return response()->json(['errors' => true]);
        }

        $company = \App\Company::where('user_id', $user->id)->first();

        if (!$company) {
            return response()->json(['errors' => true]);
        }

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:100000000',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        if (!$company->card) {
            $card = new \App\CompanyCard();
            $card->company_id = $company->id;
        } else {
            $card = $company->card;
            $this->delete_existing_image($card);
        }

        $file = $valid_request['image'];
        $ext = $file->getClientOriginalExtension();
        $file_name = 'card-' . Str::uuid() . '.' . $ext;

        $this->make_directory();

        // Save file

        // Image::make($file)
        //     ->resize(600, 300, function ($constraint) {
        //         $constraint->aspectRatio();
        //     })
        //     ->save(storage_path('app/public') . '/cards/' . $file_name);

        $photo = Image::make($file)
            ->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode($ext, 80);

        $card->image = $file_name;

        $full_path = 'uploads/cards' . '/' . $file_name;

        Storage::disk('do_spaces')->put($full_path, $photo, 'public');

        if (!$card->save()) {
            return response()->json(['errors' => true]);
        }
        return response()->json(['success' => true, 'card' => $card]);
    }

    public function delete()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        // if not login, error
        if (!$user) {
            return response()->json(['errors' => true]);
        }

        $company = \App\Company::where('user_id', $user->id)->first();

        if (!$company) {
            return response()->json(['errors' => true]);
        }

        $this->delete_existing_image($company->card);

        if (!$company->card()->delete()) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }

    private function make_directory()
    {

        if (!File::isDirectory(storage_path('app/public') . '/cards')) {
            File::makeDirectory(storage_path('app/public') . '/cards', 0777, true, true);
        }
    }

    public function delete_existing_image($current_card)
    {
        if ($current_card->image && $current_card->image != 'default-photo.png') {

            $pathToFile = 'uploads/cards/' . $current_card->image;
            if (Storage::disk('do_spaces')->exists($pathToFile)) {
                Storage::disk('do_spaces')->delete($pathToFile);
            }

            // $pathToFile = storage_path('app/public') . '/cards/' . $current_card->image;
            // if (file_exists($pathToFile)) {
            //     unlink($pathToFile);
            // }
        }
    }
}
