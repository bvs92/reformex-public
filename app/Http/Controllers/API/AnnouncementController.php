<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    public function index()
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $announcements = \App\Announcement::orderBy('created_at', 'desc')->get();

        $announcements = $announcements->map(function ($item) {
            $item['email'] = $item->user->email;
            return $item;
        });

        return response()->json(['announcements' => $announcements, 'total' => $announcements->count()]);
    }

    public function getActive()
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $announcements = \App\Announcement::where('status', 1)->orderBy('created_at', 'desc')->get();

        return response()->json(['announcements' => $announcements, 'total' => $announcements->count()]);
    }

    public function getSingle($id)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $announcement = \App\Announcement::find($id);

        $announcement->user;

        return response()->json(['announcement' => $announcement]);
    }

    public function deleteSingle($id)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $announcement = \App\Announcement::find($id);

        if (!$announcement->delete()) {
            return response()->json(['errors' => true]);
        }

        $announcements = \App\Announcement::orderBy('created_at', 'desc')->get();

        $announcements = $announcements->map(function ($item) {
            $item['email'] = $item->user->email;
            return $item;
        });

        return response()->json(['success' => true, 'announcements' => $announcements, 'total' => $announcements->count()]);
    }

    public function toggleStatus($id)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $announcement = \App\Announcement::find($id);

        $announcement->status = !$announcement->status;

        if (!$announcement->save()) {
            return response()->json(['errors' => true]);
        }

        $announcements = \App\Announcement::orderBy('created_at', 'desc')->get();

        $announcements = $announcements->map(function ($item) {
            $item['email'] = $item->user->email;
            return $item;
        });

        return response()->json(['success' => true, 'announcements' => $announcements, 'total' => $announcements->count()]);
    }

    public function changeType(Request $request, $id)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['albastru', 'verde', 'galben'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_fields = $validator->valid();

        $announcement = \App\Announcement::find($id);

        $announcement->type = $valid_fields['type'];

        if (!$announcement->save()) {
            return response()->json(['errors' => true]);
        }

        $announcements = \App\Announcement::orderBy('created_at', 'desc')->get();

        $announcements = $announcements->map(function ($item) {
            $item['email'] = $item->user->email;
            return $item;
        });

        return response()->json(['success' => true, 'announcements' => $announcements, 'total' => $announcements->count()]);
    }

    public function store(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:2',
            'status' => ['required', Rule::in([0, 1])],
            'type' => ['required', Rule::in(['albastru', 'verde', 'galben'])],
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_fields = $validator->valid();

        $announcement = new \App\Announcement;
        $announcement->title = $valid_fields['title'];
        $announcement->status = $valid_fields['status'];
        $announcement->type = $valid_fields['type'];
        $announcement->description = $valid_fields['description'];
        $announcement->user_id = $user->id;

        if (!$announcement->save()) {
            return response()->json(['errors' => true]);
        }

        $announcements = \App\Announcement::orderBy('created_at', 'desc')->get();

        $announcements = $announcements->map(function ($item) {
            $item['email'] = $item->user->email;
            return $item;
        });

        return response()->json(['success' => true, 'announcements' => $announcements, 'total' => $announcements->count()]);
    }
}
