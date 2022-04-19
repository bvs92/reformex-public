<?php

namespace App\Http\Controllers;

use App\Banner;

class BannerController extends Controller
{

    public function __construct()
    {
        // only admin access. to be changed after adding more methods.
        $this->middleware('role:admin')->only(['index', 'show', 'create']);
    }

    // admin
    public function index()
    {
        return view('volgh.banners.admin.index');
    }

    public function show($uuid)
    {
        $banner = Banner::where('uuid', $uuid)->first();
        if (!$banner) {
            return redirect()->route('advertising.banners.personal.index');
        }
        return view('volgh.banners.admin.show')->with(['uuid' => $uuid]);
    }

    public function create()
    {
        return view('volgh.banners.admin.create');
    }
    // end admin
}
