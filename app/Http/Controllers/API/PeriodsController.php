<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodsController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('days', 'asc')->get();

        $total = $periods->count();

        if ($periods->count() < 1) {
            return response()->json(['periods' => [], 'total' => $total]);
        }

        return response()->json(['periods' => $periods, 'total' => $total]);
    }

    public function client()
    {
        $periods = Period::where('visible', 1)->orderBy('days', 'asc')->get();

        $total = $periods->count();

        if ($periods->count() < 1) {
            return response()->json(['periods' => [], 'total' => $total]);
        }

        return response()->json(['periods' => $periods, 'total' => $total]);
    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $validator = Validator::make($request->all(), [
            'days' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'isVisible' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        DB::beginTransaction();

        $period = new Period();
        $period->days = $valid_request['days'];
        $period->price = $valid_request['price'];
        $period->visible = $valid_request['isVisible'];

        if (!$period->save()) {
            DB::rollback();
            return response()->json(['errors' => true]);
        }

        DB::commit();
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $period = Period::find($id);
        if (!$period) {
            return response()->json(['errors' => true]);
        }

        $validator = Validator::make($request->all(), [
            'days' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'isVisible' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        DB::beginTransaction();

        $period->days = $valid_request['days'];
        $period->price = $valid_request['price'];
        $period->visible = $valid_request['isVisible'];

        if (!$period->save()) {
            DB::rollback();
            return response()->json(['errors' => true]);
        }

        DB::commit();
        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $period = Period::find($id);

        if (!$period) {
            return response()->json(['errors' => true]);
        }

        if (!$period->delete()) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }
}
