<?php

namespace App\Http\Controllers;

use App\Demand;
use App\Review;
use App\Timeline;
use Illuminate\Http\Request;

class ReviewsController extends Controller
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

    public function saveNew(Request $request, $demand_id)
    {
        // dd($request->all());

        $demand = Demand::findOrFail($demand_id);

        if (!$demand->belongsToMe()) {
            return redirect()->back();
        }

        $validated = $request->validate([
            'professional_id' => 'required|exists:professionals,id',
            'timeline_id' => 'required|exists:timelines,id',
            'rating-stars-value' => 'required|numeric|min:1|max:5',
            'message_review' => 'required|min:2',
        ]);

        $validated['rating'] = $validated['rating-stars-value'];
        $validated['message'] = $validated['message_review'];
        $validated['demand_id'] = $demand->id;
        $validated['user_id'] = auth()->user()->id;

        $timeline = Timeline::findOrFail($validated['timeline_id']);

        if ($timeline->demand_id != $demand->id) {
            return redirect()->back();
        }

        if ($timeline->user_id != auth()->user()->id) {
            return redirect()->back();
        }

        if (!$review = Review::create($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu!');
        }

        return redirect()->route('timeline.show.client', $timeline->id)->with('success', 'Succes! Actiunea a fost executata.');
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
        //
    }

    public function all()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        return view('volgh.reviews.all');
    }

    public function reported()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        return view('volgh.reviews.reported');
    }
}
