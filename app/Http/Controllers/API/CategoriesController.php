<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = \App\Category::orderBy('name', 'asc')->get();
        $categories->makeHidden('price');
        return response()->json(['categories' => $categories]);
    }

    public function getCategory($category_slug)
    {
        $category = \App\Category::where('slug', $category_slug)->first();

        if (!$category) {
            return response()->json(['error' => true]);
        }

        return response()->json(['category' => $category]);
    }

    public function getCategories()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $categories = \App\Category::all();
        $categories = $categories->map(function ($item) {
            $item['total_demands'] = $item->demands->count();
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
            'name' => 'required|min:3|unique:categories,name',
            'price' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        $category = new \App\Category();
        $category->uuid = $this->generateUUID();
        $category->name = $valid_request['name'];
        $category->price = (float) $valid_request['price'] * 100;
        $category->description = $request->description ? $request->description : '';
        $category->slug = $this->generateSlug($valid_request['name']);

        if (!$category->save()) {
            return response()->json(['errors' => 'Oups! Ceva nu a functionat corect.']);
        }

        $category['total_demands'] = 0;

        return response()->json(['success' => true, 'category' => $category]);

    }

    private function generateSlug($name)
    {
        $slug = Str::slug($name, '-');

        // Verifica daca exista SLUG
        $existing = \App\Category::where('slug', $slug)->first();

        if ($existing) {
            $slug = $slug . "-" . time();
        }

        return $slug;
    }

    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $category = \App\Category::find($id);

        if (!$category) {
            return response()->json(['errors' => 'Nu am gasit categoria.']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:categories,name,' . $category->id,
            'price' => 'required|integer|min:1',
            'slug' => 'required|alpha_dash|unique:categories,name,' . $category->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        $category->name = $valid_request['name'];
        $category->price = (float) $valid_request['price'] * 100;
        $category->description = $request->description ? $request->description : '';
        $category->slug = $valid_request['slug'];

        if (!$category->save()) {
            return response()->json(['errors' => 'Oups! Ceva nu a functionat corect.']);
        }

        $category['total_demands'] = $category->demands->count();
        $category->price = $category->price / 100;
        $category->demands;

        return response()->json(['success' => true, 'category' => $category]);

    }

    public function destroyCategory($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $category = \App\Category::find($id);

        if (!$category) {
            return response()->json(['errors' => 'Nu am gasit categoria.']);
        }

        $general = \App\Category::where('slug', 'general')->first();

        if ($category->demands && $category->demands()->count() > 0) {
            foreach ($category->demands as $demand) {
                if ($demand->categories->count() > 1) {
                    $demand->categories()->where('category_id', $id)->delete(); // delete association from category_demand table
                } else {
                    // muta in general
                    $demand->categories()->save($general);
                }
            }
        }

        // delete category
        if (!$category->delete()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
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
        while (Category::where('uuid', $id)->get()->count() > 0) {
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

    public function saveUserCategories(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $categories = $request->categories;
        $categories_ids = [];
        foreach ($categories as $item) {
            array_push($categories_ids, $item['id']);
        }

        if (count($categories_ids) < 1) {
            return response()->json(['errors' => true]);
        }

        // return response()->json($categories_ids);

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        if (!$user->professional->categories()->sync($categories_ids)) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function deleteCategory(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $category_id = $request->id;

        if (!$user->professional->categories()->detach($category_id)) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }
}
