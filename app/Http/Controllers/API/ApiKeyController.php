<?php

namespace App\Http\Controllers\API;

use App\ApiKey;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Not allowed.']);
        }

        $keys = ApiKey::all();
        return response()->json(['keys' => $keys]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Not allowed.']);
        }

        $key = new ApiKey();
        $key->key = Str::uuid();

        if (!$key->save()) {
            return response()->json(['result' => false]);
        }

        $keys = \App\ApiKey::all();

        return response()->json(['result' => true, 'keys' => $keys]);

    }

    public function delete($key_id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Not allowed.']);
        }

        $key = ApiKey::find($key_id);

        if (!$key) {
            return response()->json(['result' => false]);
        }

        if (!$key->delete()) {
            return response()->json(['result' => false]);
        }

        return response()->json(['result' => true]);
    }
}
