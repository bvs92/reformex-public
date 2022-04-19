<?php

namespace App\Http\Controllers;

use App\User;
use App\Demand;
use App\DemandReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReportDemandNotification;

class DemandReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = DemandReport::all();
        return view('volgh.demand_reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
         $demand = Demand::findOrFail($id);


         // verifica daca utilizatorul este profesionist
        if(!auth()->user()->isPro()){
            return redirect()->back();
        }

        // verifica daca este cumparata
        if(!$demand->isBought(auth()->user()->professional->id)){
            return redirect()->back();
        }

        // verifica daca este deja raportata de utilizator (daca este deja raportata de user, raportarea nu mai este permisa)
        if($demand->isReportedBy(auth()->user())){
            return response()->view('errors.403', [], 403);
            // return redirect()->back();
        }

        // verifica daca cererea este marcata ca valida (daca este valida, raportarea nu este permisa)
        if($demand->isVerified()){
            return response()->view('errors.403', [], 403);
            // return redirect()->back();
        }

        // verifica daca cererea este marcata ca invalida (daca este invalida, raportarea nu este permisa)
        if($demand->isFalse()){
            return response()->view('errors.403', [], 403);
            // return redirect()->back();
        }

         return view('volgh.demand_reports.create', compact('demand'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $demand = Demand::findOrFail($id);
        // dd($demand);

        // verifica daca cererea este cumparata


        // verifica daca utilizatorul este profesionist
        if(!auth()->user()->isPro()){
            return redirect()->back();
        }

        // verifica daca este cumparata
        if(!$demand->isBought(auth()->user()->professional->id)){
            return redirect()->back();
        }

        // verifica daca este deja raportata de utilizator
        if($demand->isReportedBy(auth()->user())){
            return redirect()->back();
        }
        
        // verifica daca cererea este marcata ca valida (daca este valida, raportarea nu este permisa)
        if($demand->isVerified()){
            // return response()->view('errors.403', [], 403);
            return redirect()->back();
        }

        // verifica daca cererea este marcata ca invalida (daca este invalida, raportarea nu este permisa)
        if($demand->isFalse()){
            // return response()->view('errors.403', [], 403);
            return redirect()->back();
        }


        $validated = $request->validate([
            'message'   => 'required|min:2'
        ]);

        $validated['user_id'] = auth()->user()->id;
        $validated['demand_id'] = $demand->id;

        if(!$report = DemandReport::create($validated)){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        // get the admins
        $admins = User::role('admin')->get();
        Notification::send($admins, new ReportDemandNotification(auth()->user(), $demand));

        return redirect()->route('demands.show', $demand->id)->with('success', 'Actiunea a fost executata cu succes.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $report = DemandReport::findOrFail($id);

        // check ownership
        $this->authorize('update', $report);

        return view('volgh.demand_reports.show', compact('report'));
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


    public function changeStatus(Request $request, $id)
    {
        $report = DemandReport::findOrFail($id);

        // check ownership
        $this->authorize('update', $report);

        if($report->status == ''){
            // dd('este activa');
            $report->status = '1';
        } else {
            // dd('este terminata');
            $report->status = '0';
        }

        if(!$report->save()){
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('report.show.client', $reports->id)->with('success', 'Actiunea executata cu succes.');
    }


    public function complete(Request $request, $id)
    {
        $report = DemandReport::findOrFail($id);

        // check ownership
        $this->authorize('update', $report);

        $report->status = '1'; // mark as complete

        if(!$report->save()){
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('demands_reports.show', $report->id)->with('success', 'Actiune executata cu succes.');
    }

    public function close(Request $request, $id)
    {
        $report = DemandReport::findOrFail($id);

        // check ownership
        $this->authorize('update', $report);

        $report->status = '2'; // mark as closed

        if(!$report->save()){
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('demands_reports.show', $report->id)->with('success', 'Actiune executata cu succes.');
    }

}
