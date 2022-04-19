<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subscriptions.create');
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
            'name'  => 'required|min:2|max:255|unique:subscriptions,name'
        ]);

        $validated['type'] = Str::slug($validated['name'], '-');


        // Verifica daca exista deja tipul (pe baza numelui)
        $subscription = Subscription::where('type', $validated['type'])->first();
        if($subscription){
            // Daca exista, concateneaza timpoul curent.
            $validated['type'] = $validated['type'] . '-' . time();
        }

        
        if(!Subscription::create($validated)){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('subscriptions.index')->with('success', 'Tipul de abonament a fost creat cu succes.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        return view('subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        return view('subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'name'  => 'required|min:2|max:255|unique:subscriptions,name,' . $subscription->id
        ]);

        $validated['type'] = Str::slug($validated['name'], '-');


        // Verifica daca exista deja tipul (pe baza numelui)
        $subs = Subscription::where('type', $validated['type'])->first();
        if($subs && $subs->id != $subscription->id){
            // Daca exista, concateneaza timpoul curent.
            $validated['type'] = $validated['type'] . '-' . time();
        }

        
        if(!$subscription->update($validated)){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('subscriptions.index')->with('success', 'Tipul de abonament a fost creat cu succes.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $s = $subscription;
        if(!$subscription->delete()){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        $s->users()->detach();
        return redirect()->route('subscriptions.index')->with('success', 'Tipul de abonament a fost eliminat cu succes.');
    }
}
