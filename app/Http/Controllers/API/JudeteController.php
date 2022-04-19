<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JudeteController extends Controller
{
    public function getAll()
    {
        $judete = \App\Judet::orderBy('name', 'asc')->get();

        return response()->json(['judete' => $judete, 'total' => $judete->count()]);
    }

    public function getLocation($location_slug)
    {
        $location = \App\Judet::where('slug', $location_slug)->first();

        if (!$location) {
            return response()->json(['error' => true]);
        }

        return response()->json(['location' => $location]);
    }

    public function getPersonal()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $judete = $user->judets;

        return response()->json(['judete' => $judete, 'total' => $judete->count()]);
    }

    public function deleteJudet(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $judet_id = $request->id;

        if (!$user->judets()->detach($judet_id)) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function saveUserJudete(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $judete = $request->judete;
        $judete_ids = [];
        foreach ($judete as $item) {
            array_push($judete_ids, $item['id']);
        }

        if (count($judete_ids) < 1) {
            return response()->json(['errors' => true]);
        }

        // return response()->json($judete_ids);

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        if (!$user->judets()->sync($judete_ids)) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }
}
