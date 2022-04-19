<?php
namespace App\Utility;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UploadFile
{

    public static function workProject($file)
    {
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $random = Str::uuid();

        $file_name = time() . "-" . $random . '.' . $ext;

        $infos = [
            'ext' => $ext,
            'mime_type' => $mimeType,
            'file_name' => $file_name,
        ];

        // Storage::disk('public')->putFileAs('work_projects', $file, $file_name);

        // if (!File::isDirectory(public_path('storage/work_projects'))) {
        //     File::makeDirectory(public_path('storage/work_projects'), 0777, true, true);
        // }

        if (!File::isDirectory(storage_path('app/public') . '/work_projects')) {
            File::makeDirectory(storage_path('app/public') . '/work_projects', 0777, true, true);
        }

        if (exif_imagetype($file)) {
            // your image is valid

            // Save file
            $photo = Image::make($file)
                ->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode($ext, 80);
            // Image::make($file)
            //     ->resize(1000, null, function ($constraint) {
            //         $constraint->aspectRatio();
            //     })
            //     ->save(storage_path('app/public') . '/work_projects/' . $file_name);

        }

        $full_path = 'uploads/work_projects' . '/' . $file_name;

        // Storage::disk('public')->putFileAs($type_path, $file, $file_name);
        Storage::disk('do_spaces')->put($full_path, $photo, 'public');

        return $infos;
    }

}
