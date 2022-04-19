<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Utility\UploadFile;
use App\WorkProject;
use App\WorkProjectPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminWorkProjectsController extends Controller
{
    public function initialize()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $projects = WorkProject::all();

        $projects = $projects->map(function ($item) {
            $item['number_photos'] = $item->photos->count();
            $item->makeHidden(['photos']);
            return $item;
        });

        return response()->json(['projects' => $projects, 'total' => $projects->count()]);
    }

    public function initializeCategory($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        // if (!$user->isAdmin()) {
        //     return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        // }

        $category = \App\ProjectCategory::where('uuid', $uuid)->first();

        if (!$category) {
            return response()->json(['errors' => true]);
        }

        $projects = $category->projects;

        $projects = $projects->map(function ($item) {
            $item['number_photos'] = $item->photos->count();
            $item['user_email'] = $item->user->email;
            $item->makeHidden(['photos', 'user']);
            return $item;
        });

        return response()->json(['projects' => $projects, 'total' => $projects->count()]);
    }

    public function getProject($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $project = WorkProject::where('uuid', $uuid)->first();

        if (!$project) {
            return response()->json(['errors' => true]);
        }

        $project->photos;

        return response()->json(['project' => $project]);
    }

    public function update(Request $request, $uuid)
    {

        // return response()->json(['res' => $request->all()]);
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $project = WorkProject::where('uuid', $uuid)->first();

        if (!$project) {
            return response()->json(['errors' => true]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:2|max:255',
            'description' => 'required|min:2',
            'categories' => 'required|exists:project_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_project = $validator->valid();
        $project->title = $validated_project['title'];
        $project->description = $validated_project['description'];

        $validated_project['categories'] = (explode(',', $validated_project['categories']));
        $final_categories = collect($validated_project['categories'])->map(function ($item) {
            return (int) $item;
        });

        if ($final_categories->count() < 1) {
            return response()->json(['errors' => true]);
        }

        // begin transaction
        DB::beginTransaction();
        if (!$project->save()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        $project->categories()->sync(json_decode($final_categories));

        DB::commit();

        return response()->json(['result' => true, 'project' => $project]);
    }

    public function uploadPhotos(Request $request, $uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $project = WorkProject::where('uuid', $uuid)->first();
        if (!$project) {
            return response()->json(['errors' => true]);
        }

        if ($request->hasFile('the_files')) {
            $all_files = $request->file('the_files');
            foreach ($all_files as $one_file) {
                $infos = UploadFile::workProject($one_file);

                $wirkProjectPhoto = new WorkProjectPhoto();
                $wirkProjectPhoto->work_project_id = $project->id;
                $wirkProjectPhoto->user_id = $project->user_id;
                $wirkProjectPhoto->name = $infos['file_name'];
                $wirkProjectPhoto->extension = $infos['ext'];
                // $wirkProjectPhoto->path = null;
                $wirkProjectPhoto->mime_type = $infos['mime_type'];

                if (!$wirkProjectPhoto->save()) {
                    return response()->json(['errors' => true]);
                }
            }
        }

        // $project = WorkProject::where('uuid', $uuid)->firstOrFail();
        $project->photos;
        $project->categories;

        return response()->json(['result' => true, 'project' => $project]);

    }

    public function destroy($uuid)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $project = WorkProject::where('uuid', $uuid)->first();
        if (!$project) {
            return response()->json(['errors' => true]);
        }

        DB::beginTransaction();

        if ($project->photos && $project->photos->count() > 0) {
            foreach ($project->photos as $photo) {
                if (!$photo->delete()) {
                    DB::rollBack();
                    return response()->json(['errors' => true]);
                }
            }
        }

        if ($project->categories && $project->categories->count() > 0) {
            $project->categories()->detach();
        }

        if (!$project->delete()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();

        return response()->json(['result' => true]);

    }

    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while (\App\WorkProject::where('uuid', $id)->get()->count() > 0) {
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
