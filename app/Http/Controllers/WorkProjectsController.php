<?php

namespace App\Http\Controllers;

use App\Personal\FilesClass;
use App\ProjectCategory;
use App\WorkProject;
use App\WorkProjectPhoto;
use Illuminate\Http\Request;

class WorkProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = WorkProject::paginate(10);

        return view('volgh.work_projects.index', [
            'projects' => $projects,
        ]);
    }

    public function personal()
    {
        return view('volgh.work_projects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProjectCategory::all();
        return view('volgh.work_projects.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->all());

        $validated = $request->validate([
            'title' => 'required|min:2|max:255',
            'description' => 'nullable',
            'city' => 'nullable|max:255',
            'postal_code' => 'nullable|numeric',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'work_project_photos' => 'required',
            'categories' => 'required|exists:project_categories,id',
        ]);

        $validated['user_id'] = auth()->user()->id;

        // dd($request->file('work_project_photos'));
        // if($request->files('work_project_photos')->count() < 1){
        //     return redirect()->back()->with('error', 'Am intampinat erori. Ceva in neregula aici.');
        // }

        if (!$project = WorkProject::create($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        // Attach acategories
        $project->categories()->attach($validated['categories']);

        // dd($request->file('work_project_photos'));

        if ($request->hasFile('work_project_photos')) {
            $all_files = $request->file('work_project_photos');
            // dd($all_files);

            foreach ($all_files as $one_file) {
                // $infos = $this->uploadFile($one_file);
                $infos = FilesClass::upload('work_projects', $one_file);

                // dd($infos);

                $work_project_photo = new WorkProjectPhoto();
                $work_project_photo->work_project_id = $project->id;
                $work_project_photo->user_id = auth()->user()->id;
                $work_project_photo->name = $infos['file_name'];
                $work_project_photo->extension = $infos['ext'];
                // $work_project_photo->path = null;
                $work_project_photo->mime_type = $infos['mime_type'];

                $work_project_photo->save();
            }

        }

        return redirect()->route('work-projects.index')->with('success', 'Proitectul a fost creat cu succes.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $project = WorkProject::where('uuid', $uuid)->firstOrFail();
        $project->photos;
        $project->categories;

        if (auth()->user()->isAdmin()) {
            return view('volgh.work_projects.show_admin', [
                'current_project' => $project,
            ]);
        } else {
            return view('volgh.work_projects.show', [
                'current_project' => $project,
            ]);
        }
    }

    public function showUUID($uuid)
    {
        $project = WorkProject::findOrFail($id);

        return view('volgh.work_projects.show', [
            'current_project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = WorkProject::findOrFail($id);
        $categories = ProjectCategory::all();

        return view('volgh.work_projects.edit', [
            'current_project' => $project,
            'categories' => $categories,
        ]);
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
        $project = WorkProject::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|min:2|max:255',
            'description' => 'nullable',
            'city' => 'nullable|max:255',
            'postal_code' => 'nullable|numeric',
            'lat' => 'nullable',
            'lng' => 'nullable',
        ]);

        $validated['user_id'] = auth()->user()->id;

        if (!$project->update($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('work-projects.index')->with('success', 'Proiectul a fost salvat cu succes.');
    }

    public function updateCategories(Request $request, $id)
    {
        $validated = $request->validate([
            'categories' => 'required|exists:project_categories,id',
        ]);

        $project = WorkProject::findOrFail($id);

        $project->categories()->sync($validated['categories']);

        return redirect()->route('work-projects.edit', $project->id)->with('success', 'Proiectul a fost salvat cu succes.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = WorkProject::findOrFail($id);

        if ($project->photos && $project->photos->count() > 0) {

            foreach ($project->photos as $thePhoto) {
                $pathToFile = public_path() . '/storage\/work_projects\/' . $thePhoto->name;
                if (file_exists($pathToFile)) {
                    unlink($pathToFile);
                }

                $thePhoto->delete();
            }
        }

        if ($project->categories && $project->categories->count() > 0) {
            $project->categories()->detach();
        }

        if (!$project->delete()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('work-projects.index')->with('success', 'Proitectul a fost eliminat cu succes.');
    }

}
