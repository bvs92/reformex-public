<?php

namespace App\Http\Controllers;

use App\Company;

class InactiveController extends Controller
{
    public function inactive()
    {
        $user = auth()->user();

        if ($user->company) {
            $company = $user->company;
        } else {
            $company = null;
        }

        return view('volgh.users.inactive', compact(['company']));
    }
}
