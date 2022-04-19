<?php

namespace App\Http\Controllers;

use App\Demand;
use App\Events\TimelineClientTurnOffConversationEvent;
use App\Events\TimelineClientTurnOnConversationEvent;
use App\Timeline;
use App\Winner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function indexPro()
    {
        $timelines = Timeline::where('professional_id', auth()->user()->professional->id)->where('delete_pro', 0)->orderBy('created_at', 'desc')->paginate(10);
        $total_timelines = Timeline::where('professional_id', auth()->user()->professional->id)->where('delete_pro', 0)->count();
        return view('volgh.timeline.indexPro', compact(['timelines', 'total_timelines']));
    }

    public function indexClient()
    {
        // timeline pentru client => adauga user_id in timelines.
        $timelines = Timeline::where('user_id', auth()->user()->id)->where('delete_client', 0)->orderBy('created_at', 'desc')->paginate(10);
        $total_timelines = Timeline::where('user_id', auth()->user()->id)->where('delete_client', 0)->count();
        return view('volgh.timeline.indexClient', compact(['timelines', 'total_timelines']));
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
        $demand = Demand::findOrFail($id);
        $professional = auth()->user()->professional;
        $quotes = $professional->quotes()->where('demand_id', $demand->id)->get();
        $unlocked_demand = $demand->professionals()->where('user_id', $professional->user_id)->first();

        // dd($unlocked_demand);

        if (!$unlocked_demand || $unlocked_demand == null) {
            return redirect()->route('demands.index');
        }

        return view('volgh.timeline.show', compact([
            'demand',
            'professional',
            'quotes',
            'unlocked_demand',
        ]));

    }

    public function showById($id)
    {
        $timeline = Timeline::findOrFail($id);
        $demand = $timeline->demand;
        $professional = $timeline->professional;

        $conversations = collect();

        $quotes = $professional->quotes()->where('demand_id', $demand->id)->get();

        // get user (demand owner) messages
        $messages = $demand->client_messages()->where('user_id', $demand->user_id)->get();

        // Combine quotes and messages into one collection
        // $conversations = $quotes->merge($messages)->sortBy('created_at');
        $conversations = $quotes->concat($messages)->sortBy('created_at');

        // dd($conversations->keys()->all());

        // foreach($conversations as $item){
        //     echo '<pre>';
        //     if($item->professional_id)
        //         echo $item;
        //     // echo $item->keys;
        // }

        // dd($conversations);
        // Order collection elements ASC

        $unlocked_demand = $demand->professionals()->where('user_id', $professional->user_id)->first();

        // dd($unlocked_demand);

        if (!$unlocked_demand || $unlocked_demand == null) {
            return redirect()->route('demands.index');
        }

        return view('volgh.timeline.show-second', compact([
            'timeline',
            'demand',
            'professional',
            'quotes',
            'unlocked_demand',
            'conversations',
        ]));

    }

    public function showByIdForPro($id)
    {

        // verifica daca este PRO
        if (!auth()->user()->isPro()) {
            return redirect()->route('home');
        }

        $timeline = Timeline::findOrFail($id);
        $demand = $timeline->demand;
        $professional = $timeline->professional;

        $conversations = collect();

        $quotes = $professional->quotes()->where('demand_id', $demand->id)->get();

        // get user (demand owner) messages
        $messages = $timeline->client_messages()->get();

        // Combine quotes and messages into one collection
        // $conversations = $quotes->merge($messages)->sortBy('created_at');
        $conversations = $quotes->concat($messages)->sortBy('created_at');

        // dd($conversations->keys()->all());

        // foreach($conversations as $item){
        //     echo '<pre>';
        //     if($item->professional_id)
        //         echo $item;
        //     // echo $item->keys;
        // }

        // dd($conversations);
        // Order collection elements ASC

        $unlocked_demand = $demand->professionals()->where('user_id', $professional->user_id)->first();

        // dd($unlocked_demand);

        // if(!$unlocked_demand || $unlocked_demand == null){
        //     return redirect()->route('demands.index');
        // }

        // Verifica daca timeline apartine user autentificat
        // if($professional->id != auth()->user()->professional->id){
        //     return redirect()->route('demands.index');
        // }

        $this->authorize('update_pro', $timeline);

        // if($demand->getNumberBought() >= $demand->maximumOffers()){
        //     return redirect()->route('demands.index');
        //     // dd($demand->getNumberBought());
        // }

        return view('volgh.timeline.pro', compact([
            'timeline',
            'demand',
            'professional',
            'quotes',
            'unlocked_demand',
            'conversations',
        ]));

    }

    public function showByIdForProUUID($uuid)
    {

        // verifica daca este PRO
        if (!auth()->user()->isPro()) {
            return redirect()->route('home');
        }

        // $timeline = Timeline::findOrFail($id);
        $timeline = Timeline::where('uuid', $uuid)->first();
        if (!$timeline) {
            return redirect()->back();
        }

        $demand = $timeline->demand;
        // $demand->categories;
        $professional = $timeline->professional;

        $conversations = collect();

        // $quotes = $professional->quotes()->where('demand_id', $demand->id)->get();
        $quotes = $professional->quotes()->where('demand_id', $timeline->demand_id)->get();

        // get user (demand owner) messages
        $messages = $timeline->client_messages()->get();

        // $prospects = \App\Prospect::where('timeline_id', $timeline->id)->get();

        // Combine quotes and messages into one collection
        // $conversations = $quotes->merge($messages)->sortBy('created_at');
        $conversations = $quotes->concat($messages)->sortBy('created_at');
        // $conversations = $conversations->concat($prospects)->sortBy('created_at');

        // dd(\App\Prospect::where('timeline_id', $timeline->id)->get());

        // dd($conversations);

        // dd($conversations->keys()->all());

        // foreach($conversations as $item){
        //     echo '<pre>';
        //     if($item->professional_id)
        //         echo $item;
        //     // echo $item->keys;
        // }

        // dd($conversations);
        // Order collection elements ASC

        // if($demand){
        //     $unlocked_demand = $demand->professionals()->where('user_id', $professional->user_id)->first();
        // } else {
        //     $unlocked_demand = null;
        // }

        // get unlocked demand.
        $unlocked_demand = DB::table('demand_professional')->where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->first();

        $demand_cost = DB::table('activities')->where('demand_id', $timeline->demand_id)->where('user_id', $professional->user_id)->first();

        $winner = Winner::where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->first();

        // dd($unlocked_demand);

        // if(!$unlocked_demand || $unlocked_demand == null){
        //     return redirect()->route('demands.index');
        // }

        // Verifica daca timeline apartine user autentificat
        // if($professional->id != auth()->user()->professional->id){
        //     return redirect()->route('demands.index');
        // }

        $this->authorize('update_pro', $timeline);

        // if($demand->getNumberBought() >= $demand->maximumOffers()){
        //     return redirect()->route('demands.index');
        //     // dd($demand->getNumberBought());
        // }

        return view('volgh.timeline.pro', compact([
            'timeline',
            'demand',
            'professional',
            'quotes',
            'unlocked_demand',
            'conversations',
            'winner',
            'demand_cost',
        ]));

    }

    public function showByIdForClient($id)
    {
        $timeline = Timeline::findOrFail($id);

        $this->authorize('update_client', $timeline);

        $demand = $timeline->demand;
        $professional = $timeline->professional;

        $conversations = collect();

        $quotes = $professional->quotes()->where('demand_id', $demand->id)->get();

        // get user (demand owner) messages
        $messages = $timeline->client_messages()->get();

        // Combine quotes and messages into one collection
        // $conversations = $quotes->merge($messages)->sortBy('created_at');
        $conversations = $quotes->concat($messages)->sortBy('created_at');

        // dd($conversations);

        // dd($conversations->keys()->all());

        // foreach($conversations as $item){
        //     echo '<pre>';
        //     if($item->professional_id)
        //         echo $item;
        //     // echo $item->keys;
        // }

        // dd($conversations);
        // Order collection elements ASC

        // $unlocked_demand = $demand->professionals()->where('user_id', $professional->user_id)->first(); // ???

        $unlocked_demand = DB::table('demand_professional')->where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->first();

        $demand_cost = DB::table('activities')->where('demand_id', $timeline->demand_id)->where('user_id', $professional->user_id)->first();

        $winner = Winner::where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->first();

        // dd($unlocked_demand);

        // if(!$unlocked_demand || $unlocked_demand == null){
        //     return redirect()->route('demands.index');
        // }

        return view('volgh.timeline.client', compact([
            'timeline',
            'demand',
            'professional',
            'quotes',
            'unlocked_demand',
            'conversations',
            'demand_cost',
            'winner',
        ]));

    }

    public function showByIdForClientUUID($uuid)
    {
        // $timeline = Timeline::findOrFail($id);
        $timeline = Timeline::where('uuid', $uuid)->first();
        if (!$timeline) {
            return redirect()->back();
        }

        $this->authorize('update_client', $timeline);

        $demand = $timeline->demand;
        // $demand->categories;

        $professional = $timeline->professional;

        $conversations = collect();

        $quotes = $professional->quotes()->where('demand_id', $timeline->demand_id)->get();

        // get user (demand owner) messages
        $messages = $timeline->client_messages()->get();

        // Combine quotes and messages into one collection
        // $conversations = $quotes->merge($messages)->sortBy('created_at');
        $conversations = $quotes->concat($messages)->sortBy('created_at');

        // dd($conversations->keys()->all());

        // foreach($conversations as $item){
        //     echo '<pre>';
        //     if($item->professional_id)
        //         echo $item;
        //     // echo $item->keys;
        // }

        // dd($conversations);
        // Order collection elements ASC

        // $unlocked_demand = $demand->professionals()->where('user_id', $professional->user_id)->first(); // ???

        $unlocked_demand = DB::table('demand_professional')->where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->first();

        $demand_cost = DB::table('activities')->where('demand_id', $timeline->demand_id)->where('user_id', $professional->user_id)->first();

        $winner = Winner::where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->first();

        // dd($unlocked_demand);

        // if(!$unlocked_demand || $unlocked_demand == null){
        //     return redirect()->route('demands.index');
        // }

        return view('volgh.timeline.client', compact([
            'timeline',
            'demand',
            'professional',
            'quotes',
            'unlocked_demand',
            'conversations',
            'demand_cost',
            'winner',
        ]));

    }

    public function showByIdForAdminUUID($uuid)
    {}

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

    public function deleteByClient($id)
    {
        $timeline = Timeline::findOrFail($id);

        $this->authorize('update_client', $timeline);

        // eliminam mesajele din conversatie?
        if ($timeline->client_messages && $timeline->client_messages()->count() > 0) {
            foreach ($timeline->client_messages as $client_message) {
                if ($client_message->files && $client_message->files->count() > 0) {

                    foreach ($client_message->files as $theFile) {
                        // $files->push($theFile);
                        // $pathToFile = public_path() . '/storage\/quotes\/' . $theFile->name;
                        // if(file_exists($pathToFile)){
                        //     unlink($pathToFile);
                        // }

                        $theFile->delete();
                    }
                }

                $client_message->delete();
            }
        }

        // check if PRO marked for deleting. - mark timeline available for delete: delete_client = true
        // Daca PRo a sters conversatia sa si a marcat ca TRUE delete_pro, atunci elimina timeline complet.
        if ($timeline->deleteFromPro() == 1) {

            // verifica si sterge winners
            $winner = Winner::where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->first();
            if ($winner && $winner->count() > 0) {
                $winner->delete();
            }

            // verifica si sterge prospects
            if ($timeline->prospects && $timeline->prospects()->count() > 0) {
                $timeline->prospects()->delete();
            }

            // verifica si sterge demand_professional (cumparator cerere)
            DB::table('demand_professional')->where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->delete();

            if (!$timeline->delete()) {
                return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
            }
        } else {
            $timeline->delete_client = true;
            if ($timeline->isActive()) {
                $timeline->status = '1'; // mark as inactive.
            }
            $timeline->save();
        }

        return redirect()->route('timeline.index.client')->with('success', 'Actiune executata cu succes.');
    }

    public function deleteByPro($id)
    {
        $timeline = Timeline::findOrFail($id);

        $this->authorize('update_pro', $timeline);

        // daca cererea exista inca si este in activa, redirect back
        if ($timeline->demand) {
            return redirect()->back()->with('error', 'Cererea inca exista. Nu puteti sterge aceasta conversatie.');
        }

        if ($timeline->quotes && $timeline->quotes()->count() > 0) {
            foreach ($timeline->quotes as $quote) {
                if ($quote->files && $quote->files->count() > 0) {

                    foreach ($quote->files as $theFile) {
                        // $files->push($theFile);
                        // $pathToFile = public_path() . '/storage\/quotes\/' . $theFile->name;
                        // if(file_exists($pathToFile)){
                        //     unlink($pathToFile);
                        // }

                        $theFile->delete();
                    }
                }

                $quote->delete();
            }
        }

        // check if PRO marked for deleting. - mark timeline available for delete: delete_client = true

        // check if PRO marked for deleting. - mark timeline available for delete: delete_client = true
        // Daca PRo a sters conversatia sa si a marcat ca TRUE delete_pro, atunci elimina timeline complet.
        if ($timeline->deleteFromClient() == 1) {

            // verifica si sterge winners
            $winner = Winner::where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->first();
            if ($winner && $winner->count() > 0) {
                $winner->delete();
            }

            // verifica si sterge prospects
            if ($timeline->prospects && $timeline->prospects()->count() > 0) {
                $timeline->prospects()->delete();
            }

            // verifica si sterge demand_professional (cumparator cerere)
            DB::table('demand_professional')->where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->delete();

            if (!$timeline->delete()) {
                return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
            }
        } else {
            $timeline->delete_pro = true;
            $timeline->save();
        }

        return redirect()->route('timeline.index.pro')->with('success', 'Actiune executata cu succes.');
    }

    public function changeStatus(Request $request, $id)
    {
        $timeline = Timeline::findOrFail($id);

        $this->authorize('update', $timeline);

        if ($timeline->isActive()) {
            // dd('este activa');
            $timeline->status = '1';
        } else {
            // dd('este terminata');
            $timeline->status = '0';
        }

        if (!$timeline->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('timeline.show.client', $timeline->id)->with('success', 'Actiunea executata cu succes.');
    }

    public function changeStatusUUID(Request $request, $uuid)
    {
        // $timeline = Timeline::findOrFail($id);

        $timeline = Timeline::where('uuid', $uuid)->first();
        if (!$timeline) {
            return redirect()->back();
        }

        $this->authorize('update_client', $timeline);

        if ($timeline->isActive()) {
            // dd('este activa');
            $timeline->status = '1';

            // send event to notify PRO
            event(new TimelineClientTurnOffConversationEvent($timeline));

        } else {
            // dd('este terminata');
            $timeline->status = '0';
            // send event to notify PRO
            event(new TimelineClientTurnOnConversationEvent($timeline));

        }

        if (!$timeline->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('timeline.show.client.uuid', $timeline->uuid)->with('success', 'Actiunea executata cu succes.');
    }

    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while (\App\Timeline::where('uuid', $id)->get()->count() > 0) {
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
