<?php

namespace App\Http\Controllers;

class PeriodsController extends Controller
{
    public function __construct()
    {
        // only admin access. to be changed after adding more methods.
        $this->middleware('role:admin');
    }

    // admin
    public function index()
    {
        return view('volgh.periods.index');
    }
    // end admin
}
