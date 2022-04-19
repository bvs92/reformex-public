<?php

namespace App\Http\Controllers;

class ApiKeyController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return back();
        }

        return view('volgh.keys.index');
    }
}
