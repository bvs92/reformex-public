<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    function index() {
        return view('volgh.help.index');
    }
}
