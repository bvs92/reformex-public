<?php

namespace App\Http\Controllers;

use App\Category;
use App\Demand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('aici');
        $categories = Category::latest()->get();
        $totalDemands = Demand::all()->count();
        return view('volgh.categories.index', compact(['categories', 'totalDemands']));
    }

    public function indexVue()
    {
        return view('volgh.categories.index-vue');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('volgh.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:2|max:255',
            'price' => 'required|numeric|min:1',
            'description' => 'required|min:10',
        ]);

        $validated['slug'] = Str::slug($validated['name'], '-');
        $validated['price'] = (float) $validated['price'] * 100;
        $validated['uuid'] = $this->generateUUID();

        // Verifica daca exista SLUG
        $existing = Category::where('slug', $validated['slug'])->first();

        if ($existing) {
            $validated['slug'] = $validated['slug'] . "-" . time();
        }

        // dd($validated);

        if (!Category::create($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu');
        }

        return redirect()->route('categories.index')->with('success', 'Categorie creata cu succes.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('volgh.categories.show', compact('category'));
    }

    public function showVue($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category['total_demands'] = $category->demands->count();
        $category->price = $category->price / 100;
        $category->demands;
        return view('volgh.categories.show-vue', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|min:2|max:255',
            'price' => 'required|numeric|min:1',
            'slug' => 'required|min:2|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|min:10',
        ]);

        // Transform credit in UNITS
        $validated['price'] = $validated['price'] * 100;

        if (!$category->update($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('categories.show', $category->id)->with('success', 'Categoria a fost modificata cu succes.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
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
}
