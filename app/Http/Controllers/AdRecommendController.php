<?php

namespace App\Http\Controllers;

use App\AdRecommendCompany;

class AdRecommendController extends Controller
{
    public function __construct()
    {
        // only admin access. to be changed after adding more methods.
        $this->middleware('role:admin')->only(['index', 'show', 'create']);
    }

    // admin
    public function index()
    {
        return view('volgh.ads_recommend.admin.index');
    }

    public function show($uuid)
    {
        $adCompany = AdRecommendCompany::where('uuid', $uuid)->first();
        if (!$adCompany) {
            return redirect()->route('advertising.ad_recommend_company.personal.index');
        }
        return view('volgh.ads_recommend.admin.show')->with(['uuid' => $uuid]);
    }

    public function create()
    {
        return view('volgh.ads_recommend.admin.create');
    }
}
