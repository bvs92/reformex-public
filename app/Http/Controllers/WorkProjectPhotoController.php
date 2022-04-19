<?php

namespace App\Http\Controllers;

use App\WorkProjectPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkProjectPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = WorkProjectPhoto::findOrFail($id);

        // $pathToFile = public_path() . '/storage\/work_projects\/' . $photo->name;
        // if(file_exists($pathToFile)){
        //     unlink($pathToFile);
        // }

        $pathToFile = 'uploads/work_projects/' . $photo->name;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {
            Storage::disk('do_spaces')->delete($pathToFile);
        }

        $photo->delete();

        return redirect()->back()->with('success', 'Actiune efectuata cu succes.');
    }
}
