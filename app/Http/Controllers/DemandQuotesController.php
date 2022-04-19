<?php

namespace App\Http\Controllers;

use App\Demand;
use App\Quote;
use Illuminate\Http\Request;

class DemandQuotesController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');    
    }


    public function sendQuoteTo(Request $request, Demand $demand)
    {
        if($demand->hasProfessional(auth()->user()->professional)){
            return redirect()->back();
        }

        // dd("Here". $request->all());
        $validated = $request->validate([
            'price'   => 'nullable|numeric',
            'message' => 'required|min:5'
        ]);
        
        dd(auth()->user());

        $validated['demand_id'] = $demand->id;
        $validated['user_id'] = auth()->user()->id;


        if(!Quote::create($validated)){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show', $demand)->with('success', 'Cotatie de pret trimisa cu success.');
    }
}
