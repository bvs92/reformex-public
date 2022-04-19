<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function download($type, $file_name)
    {
        // $filepath = URL::asset('storage/' . $type . '/' . $file_name);

        // $contents = Storage::get(URL::asset('storage/' . $type . '/' . $file_name));
        // dd(URL::asset('storage/' . $type . '/' . $file_name));

        // dd(public_path('storage/' . $type . '/' . $file_name));

        // $filepath = URL::asset('storage/' . $type . '/' . $file_name);
        $filepath = public_path('storage/' . $type . '/' . $file_name);

        // if(file_exists($filepath)) {
        //     header('Content-Description: File Transfer');
        //     header('Content-Type: application/octet-stream');
        //     header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        //     header('Expires: 0');
        //     header('Cache-Control: must-revalidate');
        //     header('Pragma: public');
        //     header('Content-Length: ' . filesize($filepath));
        //     flush(); // Flush system output buffer
        //     readfile($filepath);
        //     die();
        // } else {
        //     http_response_code(404);
        //     die();
        // }

        if (file_exists($filepath))
        // return response()->download($filepath);
        {
            return Storage::download('public/' . $type . '/' . $file_name);
        }

        // dd($full_file);
        // if($full_file !== null)
        //     return Storage::get($full_file);
    }

}
