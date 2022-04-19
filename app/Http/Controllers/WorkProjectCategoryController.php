<?php

namespace App\Http\Controllers;

use App\ProjectCategory;
use Illuminate\Http\Request;

class WorkProjectCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        return view('volgh.work_project_categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('volgh.work_project_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'name' => 'required|min:2|max:255',
        //     'slug' => 'required|min:2|max:255|unique:project_categories,slug',
        //     'description' => 'required|min:10',
        // ]);

        // $validated['slug'] = Str::slug($validated['slug'], '-');

        // if (!ProjectCategory::create($validated)) {
        //     return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu');
        // }

        // return redirect()->route('work-projects-categories.index')->with('success', 'Categorie creata cu succes.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        $category = ProjectCategory::where('slug', $slug)->firstOrFail();

        return view('volgh.work_project_categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $category = ProjectCategory::findOrFail($id);

        // $validated = $request->validate([
        //     'name' => 'required|min:2|max:255',
        //     'slug' => 'required|min:2|max:255|unique:project_categories,slug,' . $category->id,
        //     'description' => 'required|min:10',
        // ]);

        // if (!$category->update($validated)) {
        //     return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        // }

        // return redirect()->route('work-projects-categories.show', $category->id)->with('success', 'Categoria a fost modificata cu succes.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $category = ProjectCategory::findOrFail($id);

        // if (!$category->delete()) {
        //     return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        // }

        // return redirect()->route('work-projects-categories.index')->with('success', 'Categorie a fost eliminata cu succes.');
    }
}
