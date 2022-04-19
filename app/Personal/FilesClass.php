<?php

namespace App\Personal;

use Illuminate\Support\Facades\Storage;

class FilesClass
{

    public static function test()
    {
        return "ESTE UN TEST";
    }

    public static function upload($type_path, $file)
    {

        // Get image original extension
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $random = substr(md5(mt_rand()), 0, 5);

        // dd($mimeType);

        // Compose the name
        $file_name = $type_path . '-' . time() . "-" . $random . "-" . auth()->user()->id . '.' . $ext;

        // dd($file_name);

        $infos = [
            'ext' => $ext,
            'mime_type' => $mimeType,
            'file_name' => $file_name,
        ];

        // Save file
        // Image::make($file)
        // ->resize(300, 300, function ($constraint) {
        //     $constraint->aspectRatio();
        // })
        // ->save(public_path('images/avatars/' . $file_name));

        // $resized_file = Image::make($file)
        // ->resize(800, 800, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save(public_path('storage/' . $type_path . '/' . $file_name));

        // Register file in Files table.

        $full_path = 'uploads/' . $type_path . '/' . $file_name;

        // Storage::disk('public')->putFileAs($type_path, $file, $file_name);
        Storage::disk('do_spaces')->put($full_path, $file, 'public');

        return $infos;
    }

    public function delete($path, $file)
    {
        # code...
    }
}
