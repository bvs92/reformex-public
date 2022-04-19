<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkProjectCategoryController extends Controller
{
    public function initialize()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        // if (!$user->isAdmin()) {
        //     return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        // }

        $categories = \App\ProjectCategory::all();
        $categories = $categories->map(function ($item) {
            $item['total_projects'] = $item->projects->count();
            $item->makeHidden(['projects']);
            return $item;
        });
        return response()->json(['categories' => $categories, 'total' => $categories->count()]);
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
            'name' => 'required|min:3|unique:project_categories,name',
            'url' => 'required|min:3|alpha_dash|unique:project_categories,slug',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        $category = new \App\ProjectCategory();
        $category->name = $valid_request['name'];
        $category->uuid = $this->generateUUID();
        $category->slug = strtolower($valid_request['url']);

        if (!$category->save()) {
            return response()->json(['errors' => 'Oups! Ceva nu a functionat corect.']);
        }

        $category['total_projects'] = 0;

        return response()->json(['success' => true, 'category' => $category]);

    }

    public function update(Request $request, $uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $category = \App\ProjectCategory::where('uuid', $uuid)->first();
        if (!$category) {
            return response()->json(['errors' => true]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:project_categories,name,' . $category->id,
            'slug' => 'required|min:3|alpha_dash|unique:project_categories,slug,' . $category->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // $category = \App\ProjectCategory::where('uuid', $uuid)->first();
        $category->name = $valid_request['name'];
        $category->slug = strtolower($valid_request['slug']);

        if (!$category->save()) {
            return response()->json(['errors' => 'Oups! Ceva nu a functionat corect.']);
        }

        return response()->json(['success' => true, 'category' => $category]);

    }

    public function delete($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $category = \App\ProjectCategory::where('uuid', $uuid)->first();
        if (!$category) {
            return response()->json(['errors' => true]);
        }

        if ($category->projects && $category->projects->count() > 0) {
            return response()->json(['errors' => true]);
        }

        if (!$category->delete()) {
            return response()->json(['errors' => 'Oups! Ceva nu a functionat corect.']);
        }

        return response()->json(['success' => true]);
    }

    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while (\App\ProjectCategory::where('uuid', $id)->get()->count() > 0) {
            // regenereaza daca exista
            $res = \Illuminate\Support\Str::uuid();
            // $res = rand(0, 99);
            $id = substr($res, 0, 8);
            // echo $id . '<br/>';
        }

        // echo 'JOS: ' . $id . '<br/>';
        return $id;

        // => id este unic si poate fi atasat unei cereri.
    }

}
