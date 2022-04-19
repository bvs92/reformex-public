<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class CreditsController extends Controller
{

    public function getCredit($user_id)
    {
        $user = \App\User::find($user_id);

        if (!$user) {
            return response()->json(['errors' => true]);
        }

        if (!$user->credit) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true, 'credit' => $user->getCreditAmount()]);
    }
}
