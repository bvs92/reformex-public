<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Category;
use App\Demand;
use App\Notifications\DemandBought;
use App\Notifications\ResponseForReportedDemandNotification;
use App\Notifications\TimelineAction;
use App\Professional;
use App\Prospect;
use App\Timeline;
use App\Utility\DictionaryRegex;
use App\Winner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class DemandsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only(['changeStatus', 'changeStatusUUID', 'changeStatusToVerifiedUUID', 'changeStatusToVerified', 'changeStatusToUnverifiedUUID', 'changeStatusToUnverified', 'changeStatusToFalseUUID', 'changeStatusToFalse']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $demands = Demand::latest()->get();
        // dd($demands);
        $result = $demands->map(function ($item) {
            if (!$item->hasProfessional(auth()->user()->professional)) {
                $item->email = str_repeat("*", strlen($item->email));
                $item->phone = str_repeat("*", strlen($item->phone));
                $item->name = str_repeat("*", strlen($item->name));
            }

            return $item;
        });

        // return view('volgh.demands.index', [
        //     'demands' => $result
        // ]);

        return view('volgh.demands.index-2', [
            'demands' => $result,
        ]);
    }

    public function explore()
    {
        $query = '';
        // return Demand::search($query)->get();
        $result = Demand::search('')->get();

        // check if demand is active
        $result = $result->filter(function ($item) {
            if ($item->isStateActive()) {
                return $item;
            }
        });

        // dd($result);

        $categories = Category::all();

        if (auth()->user()->isPro()) {
            $pro = auth()->user()->professional;
            //Check if any demand is bought
            $result = $result->filter(function ($item) use ($pro) {
                // Log::info("PRO ID AICI ESTE: " . $pro->id);
                if (!$item->isBought($pro->id)) {
                    return $item;
                }
            });
            // check if demand belongs to me MAKE IT WORK
            $result = $result->filter(function ($item) use ($pro) {
                if ($item->belongsToUser($pro->user) != true) {
                    return $item;
                }
            });

            return view('volgh.demands.explore', [
                'demands' => $result,
                'categories' => $categories,
            ]);

        }

        // dd($personalResult);

        return view('volgh.demands.explore-client', [
            'demands' => $result,
            'categories' => $categories,
        ]);

    }

    public function exploreVue()
    {
        #explore-demands-component

        return view('volgh.demands.explore-vue');

    }

    public function exploreVueFinal()
    {
        #explore-demands-component

        return view('volgh.demands.explore-vue-final');

    }

    public function exploreAlgolia()
    {
        #explore-demands-component

        return view('volgh.demands.explore-algolia');

    }

    public function exploreResults(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required',
            'range' => 'required|numeric',
            'categories' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        // dd($request->all());

        if ($validated['range'] == null || $validated['range'] == '' || !is_numeric($validated['range'])) {
            $validated['range'] = 1000;
        } else if ($validated['range'] < 1) {
            $validated['range'] = 1000;
        } else {
            $validated['range'] = $validated['range'] * 1000;
        }

        // dd($validated['range']);

        $query = '';
        // return Demand::search($query)->get();
        $result = Demand::search('')->with([
            'aroundLatLng' => $validated['lat'] . ',' . $validated['lng'], // Craiova
            'aroundRadius' => $validated['range'],
            'aroundPrecision' => 2000,
            'getRankingInfo' => true,
            // 'filters' => "categories:" . $filter
        ])->get();

        // check if demand is active
        $result = $result->filter(function ($item) {
            if ($item->isStateActive()) {
                return $item;
            }
        });

        $user_categories = $validated['categories'];

        // Filtreaza categoriile setate
        $result = $result->filter(function ($item) use ($user_categories) {
            // dd($categories);
            foreach ($item->categories as $cat) {
                if (in_array($cat->id, $user_categories)) {
                    return $item;
                }
            }

            // dd($item->categories);
        });

        // dd($result);

        // dd($result);

        $categories = Category::all();

        if (auth()->user()->isPro()) {
            $pro = auth()->user()->professional;
            //Check if any demand is bought
            $result = $result->filter(function ($item) use ($pro) {
                // Log::info("PRO ID AICI ESTE: " . $pro->id);
                if (!$item->isBought($pro->id)) {
                    return $item;
                }
            });

            // check if demand belongs to me MAKE IT WORK
            $result = $result->filter(function ($item) use ($pro) {
                if ($item->belongsToUser($pro->user) != true) {
                    return $item;
                }
            });

            return view('volgh.demands.explore', [
                'demands' => $result,
                'categories' => $categories,
            ]);

        }

        // dd($personalResult);

        return view('volgh.demands.explore-client', [
            'demands' => $result,
            'categories' => $categories,
        ]);
    }

    public function clientIndex()
    {
        $demands = auth()->user()->demands()->orderBy('created_at', 'DESC')->paginate(10);

        return view('volgh.demands.client-index', [
            'demands' => $demands,
        ]);
    }

    public function personalDemands()
    {

        if (!auth()->user()->isPro()) {
            return view('volgh.demands.activate-pro');
        }

        // dd(auth()->user()->professional->range ?? 1000);
        // $categories = auth()->user()->professional->categories;
        $categories = auth()->user()->professional->categories->pluck('id');

        // $filter = "";
        // $i = 0;
        // $length = count($categories);

        // foreach($categories as $value){

        //     if($length == 1){
        //         $filter .= $value;
        //     } else if($length > 1){
        //         if($i == $length - 1)
        //             $filter .= $value;
        //         else
        //             $filter .= $value . " OR ";
        //     }

        //     $i++;
        // }

        // dd($categories);

        if (auth()->user()->professional->range == null) {
            $range = 1000;
        } else if (auth()->user()->professional->range < 1000) {
            $range = 1000;
        } else {
            $range = auth()->user()->professional->range;
        }

        $query = '';
        // return Demand::search($query)->get();
        $result = Demand::search('')->with([
            'aroundLatLng' => auth()->user()->professional->lat . ',' . auth()->user()->professional->lng, //
            'aroundRadius' => $range,
            'aroundPrecision' => 10000,
            'getRankingInfo' => true,
            // 'filters' => "categories:" . $filter
        ])->orderBy('created_at', 'desc')->get();

        // dd($result);

        $pro = auth()->user()->professional;
        //Check if any demand is bought
        $result = $result->filter(function ($item) use ($pro) {
            // Log::info("PRO ID AICI ESTE: " . $pro->id);
            if (!$item->isBought($pro->id)) {
                return $item;
            }
        });

        // dd($result);

        // check if demand belongs to me MAKE IT WORK
        $result = $result->filter(function ($item) use ($pro) {
            if ($item->belongsToUser($pro->user) != true) {
                return $item;
            }
        });

        // Filtreaza categoriile setate
        $personalResult = $result->filter(function ($item) use ($categories) {
            // dd($categories);
            foreach ($item->categories as $cat) {
                if ($categories->contains($cat->id)) {
                    return $item;
                }
            }

            // dd($item->categories);
        });

        // dd($personalResult);

        return view('volgh.demands.personal', [
            'demands' => $personalResult,
        ]);

    }

    public function unlockedDemands()
    {
        return view('volgh.demands.unlocked', [
            'demands' => auth()->user()->professional->demands()->orderBy('created_at', 'desc')->paginate(10),
            'total_demands' => auth()->user()->professional->demands()->count(),
        ]);
    }

    public function reportedDemands()
    {
        $demands = Demand::all();

        $filtered = $demands->filter(function ($item) {
            return $item->reports->count() > 0;
        });
        // dd($demands); arata doar cererile care exista si care au fost raportate

        return view('volgh.demands.reported', [
            'demands' => $filtered,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd(config('services.algolia.appId'));
        // dd(config('services.algolia.apiKey'));
        $categories = Category::all();
        return view('volgh.demands.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $geolocation = [
        //     'lat' => $request->lat,
        //     'lng' => $request->lng,
        // ];

        // dd($geolocation);

        // dd(request()->categories);
        // dd(request()->validate(['categories' => 'required|exists:categories,id']));
        $validated_demand = $request->validate([
            'subject' => 'required|min:2|max:255',
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|min:2|max:255',
            'phone' => 'required|string|size:10',
            'city' => 'required|min:2|max:255',
            'message' => 'required|min:10',
        ]);

        $validated_demand['user_id'] = auth()->user()->id;
        // $geolocation = [
        //     'lat' => $request->lat,
        //     'lng' => $request->lng,
        // ];

        // $validated_demand['_geoloc'] = json_encode($geolocation);

        // dd($validated['_geoloc']);

        // dd($geolocation);

        $validated_demand['uuid'] = $this->generateUUID();

        // dd($validated_demand['uuid']);

        $validated_demand['lat'] = $request->input('lat');
        $validated_demand['lng'] = $request->input('lng');

        $validated_categories = request()->validate(['categories' => 'required|exists:categories,id']);

        // dd($validated_categories['categories']);

        if (!$demand = Demand::create($validated_demand)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        $demand->detail()->create();

        $demand->categories()->sync($validated_categories['categories']);

        return redirect()->route('demands.index')->with('success', 'Cererea a fost inregistrata.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Demand  $demand
     * @return \Illuminate\Http\Response
     */
    public function show(Demand $demand)
    {
        // If demand->isActiveFor(auth()->user()->professional->id)
        // --- verifica in demand_professional daca exista inregistrare pentru demand_id si professional_id
        // If True => cererea este deblocata
        // Else => cererea este blocata.

        if ($demand->belongsToMe()) {
            return view('volgh.demands.show-for-owner', compact('demand'));
        }

        // if isAdmin
        if (auth()->user()->isAdmin()) {
            return view('volgh.demands.show-for-admin', compact('demand'));
        }

        // if is PRO and demand is bought
        if (auth()->user()->isPro()) {
            $timeline = $demand->timelines->where('professional_id', auth()->user()->professional->id)->first();
            if ($demand->isBought(auth()->user()->professional->id)) {
                return view('volgh.demands.show', compact(['demand', 'timeline']));
            }
        }

        // if is pro and demand is not bought

        if ($demand->getNumberBought() >= $demand->maximumOffers()) {
            return redirect()->route('demands.index')->with('error', 'Numarul maxim de oferte a fost atins.');
            // dd($demand->getNumberBought());
        }

        if (!$demand->hasProfessional(auth()->user()->professional)) {
            $demand->email = str_repeat("*", strlen($demand->email));
            $demand->phone = str_repeat("*", strlen($demand->phone));
            $demand->name = str_repeat("*", strlen($demand->name));
        }

        // fake Verifica daca exista Quote trimis.
        // if(!$demand->quotes->where('professional_id', auth()->user()->professional->id)->first()){
        //     $demand->email = str_repeat("*", strlen($demand->email));
        //     $demand->phone = str_repeat("*", strlen($demand->phone));
        //     $demand->name = str_repeat("*", strlen($demand->name));
        // }

        // $quotes = $demand->quotes()->where('professional_id', auth()->user()->id)->get();

        return view('volgh.demands.show', compact('demand'));
    }

    public function showUUID($uuid)
    {
        // If demand->isActiveFor(auth()->user()->professional->id)
        // --- verifica in demand_professional daca exista inregistrare pentru demand_id si professional_id
        // If True => cererea este deblocata
        // Else => cererea este blocata.

        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        if ($demand->belongsToMe()) {
            return view('volgh.demands.show-for-owner', compact('demand'));
        }

        // if isAdmin
        if (auth()->user()->isAdmin()) {
            return view('volgh.demands.show-for-admin', compact('demand'));
        }

        $timeline = $demand->timelines->where('professional_id', auth()->user()->professional->id)->first();

        if (auth()->user()->isPro()) {
            if ($demand->isBought(auth()->user()->professional->id)) {
                // return view('volgh.demands.show', compact('demand'));
                return view('volgh.demands.show', ['demand' => $demand, 'timeline' => $timeline]);
            }
        }

        if ($demand->getNumberBought() >= $demand->maximumOffers()) {
            return redirect()->route('demands.index')->with('error', 'Numarul maxim de oferte a fost atins.');
            // dd($demand->getNumberBought());
        }

        if (!$demand->hasProfessional(auth()->user()->professional)) {
            $demand->email = str_repeat("*", strlen($demand->email));
            $demand->phone = str_repeat("*", strlen($demand->phone));
            $demand->name = str_repeat("*", strlen($demand->name));
        }

        // fake Verifica daca exista Quote trimis.
        // if(!$demand->quotes->where('professional_id', auth()->user()->professional->id)->first()){
        //     $demand->email = str_repeat("*", strlen($demand->email));
        //     $demand->phone = str_repeat("*", strlen($demand->phone));
        //     $demand->name = str_repeat("*", strlen($demand->name));
        // }

        // $quotes = $demand->quotes()->where('professional_id', auth()->user()->id)->get();

        return view('volgh.demands.show', ['demand' => $demand, 'timeline' => $timeline]);
    }

    public function showUUIDNotPro($uuid)
    {
        // If demand->isActiveFor(auth()->user()->professional->id)
        // --- verifica in demand_professional daca exista inregistrare pentru demand_id si professional_id
        // If True => cererea este deblocata
        // Else => cererea este blocata.

        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        if ($demand->belongsToMe()) {
            return view('volgh.demands.show-for-owner', compact('demand'));
        }

        // if isAdmin
        if (auth()->user()->isAdmin()) {
            return view('volgh.demands.show-for-admin', compact('demand'));
        }

        $demand->email = str_repeat("*", strlen($demand->email));
        $demand->phone = str_repeat("*", strlen($demand->phone));
        $demand->name = str_repeat("*", strlen($demand->name));

        // fake Verifica daca exista Quote trimis.
        // if(!$demand->quotes->where('professional_id', auth()->user()->professional->id)->first()){
        //     $demand->email = str_repeat("*", strlen($demand->email));
        //     $demand->phone = str_repeat("*", strlen($demand->phone));
        //     $demand->name = str_repeat("*", strlen($demand->name));
        // }

        // $quotes = $demand->quotes()->where('professional_id', auth()->user()->id)->get();

        return view('volgh.demands.show-not-pro', ['demand' => $demand]);
    }

    public function showForPro(Demand $demand)
    {
        // If demand->isActiveFor(auth()->user()->professional->id)
        // --- verifica in demand_professional daca exista inregistrare pentru demand_id si professional_id
        // If True => cererea este deblocata
        // Else => cererea este blocata.

        // if($demand->belongsToMe()){
        //     return view('volgh.demands.show', compact('demand'));
        // }

        if (!$demand->hasProfessional(auth()->user()->professional)) {
            $demand->email = str_repeat("*", strlen($demand->email));
            $demand->phone = str_repeat("*", strlen($demand->phone));
            $demand->name = str_repeat("*", strlen($demand->name));
        }

        // fake Verifica daca exista Quote trimis.
        // if(!$demand->quotes->where('professional_id', auth()->user()->professional->id)->first()){
        //     $demand->email = str_repeat("*", strlen($demand->email));
        //     $demand->phone = str_repeat("*", strlen($demand->phone));
        //     $demand->name = str_repeat("*", strlen($demand->name));
        // }

        // $quotes = $demand->quotes()->where('professional_id', auth()->user()->id)->get();

        // return view('volgh.demands.show', compact(['demand', 'quotes']));
        return view('volgh.demands.show', compact(['demand']));
    }

    // VUE - Show Demand For Pro
    public function showForProVue($uuid)
    {
        $demand = \App\Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back()->with(['error' => 'Cerere indisponibila.']);
        }

        if (!auth()->user()->isPro()) {
            return redirect()->back()->with(['error' => 'Nu aveti access la aceasta sectiune.']);
        }

        // check for BUYER

        if (!$demand->hasBuyer(auth()->user())) {
            $demand->email = str_repeat("*", strlen($demand->email));
            $demand->phone = str_repeat("*", strlen($demand->phone));
            $demand->name = str_repeat("*", strlen($demand->name));

            $demand->subject = DictionaryRegex::mask($demand->subject);
            $demand->message = DictionaryRegex::mask($demand->message);
        }

        $demand['is_bought'] = $demand->hasBuyer(auth()->user());

        $demand['report'] = $demand->reports()->where('user_id', auth()->user()->id)->first();
        $demand->categories->makeHidden(['price', 'description', 'pivot']);
        $demand['categories'] = $demand->categories;
        $demand->files;
        $demand->attachments;
        $demand['price'] = $demand->getCalculatedPriceNormal();

        return view('volgh.demands.pro.show-demand', compact(['demand']));
    }

    // end VUE - Show Demand For Pro

    public function getReportedDemands()
    {
        if (!$user = auth()->user()) {
            return redirect()->back()->with(['error' => 'Nu aveti permisiunea de a accesa sectiunea.']);
        }

        if (!$user->isPro()) {
            return redirect()->back()->with(['error' => 'Nu aveti permisiunea de a accesa sectiunea.']);
        }

        return view('volgh.demands.pro.list-reported');
    }

    public function getUnlockedDemands()
    {
        if (!$user = auth()->user()) {
            return redirect()->back()->with(['error' => 'Nu aveti permisiunea de a accesa sectiunea.']);
        }

        if (!$user->isPro()) {
            return redirect()->back()->with(['error' => 'Nu aveti permisiunea de a accesa sectiunea.']);
        }

        return view('volgh.demands.pro.list-unlocked');
    }

    public function showForProUUID($uuid)
    {

        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }
        // If demand->isActiveFor(auth()->user()->professional->id)
        // --- verifica in demand_professional daca exista inregistrare pentru demand_id si professional_id
        // If True => cererea este deblocata
        // Else => cererea este blocata.

        // if($demand->belongsToMe()){
        //     return view('volgh.demands.show', compact('demand'));
        // }

        if (!$demand->hasProfessional(auth()->user()->professional)) {
            $demand->email = str_repeat("*", strlen($demand->email));
            $demand->phone = str_repeat("*", strlen($demand->phone));
            $demand->name = str_repeat("*", strlen($demand->name));
        }

        // fake Verifica daca exista Quote trimis.
        // if(!$demand->quotes->where('professional_id', auth()->user()->professional->id)->first()){
        //     $demand->email = str_repeat("*", strlen($demand->email));
        //     $demand->phone = str_repeat("*", strlen($demand->phone));
        //     $demand->name = str_repeat("*", strlen($demand->name));
        // }

        // $quotes = $demand->quotes()->where('professional_id', auth()->user()->id)->get();

        // return view('volgh.demands.show', compact(['demand', 'quotes']));
        return view('volgh.demands.show', compact(['demand']));
    }

    public function showForOwner($id)
    {
        $demand = Demand::findOrFail($id);
        // $professionals->demand->professionals;
        // $timelines = $demand->timelines()->get();
        // dd($demand);

        if (!$demand->belongsToMe()) {
            return redirect()->route('demands.index')->with('error', 'Pagina nu poate fi accesata.');
        }

        return view('volgh.demands.show-for-owner', compact('demand'));
    }

    public function showForOwnerUUID($uuid)
    {
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }
        // dd($demand);

        if (!$demand->belongsToMe()) {
            return redirect()->route('demands.index')->with('error', 'Pagina nu poate fi accesata.');
        }

        return view('volgh.demands.show-for-owner', compact('demand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Demand  $demand
     * @return \Illuminate\Http\Response
     */
    public function edit(Demand $demand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Demand  $demand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Demand $demand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Demand  $demand
     * @return \Illuminate\Http\Response
     */

    public function destroyByOwner(Demand $demand)
    {
        $this->authorize('update', $demand);

        // delete the demand, the details and the files if any (future)

        if (!$demand->delete()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('demands.index')->with('success', 'Operatiune efectuata cu succes.');
    }

    public function destroyByOwnerWithConversations(Demand $demand)
    {
        $this->authorize('update', $demand);

        // delete the demand, the details and the files if any (future)

        // delete the owner timelines for the demand
        $client_timelines = $demand->timelines()->where('user_id', auth()->user()->id)->get();

        if ($client_timelines) {
            foreach ($client_timelines as $timeline) {
                $timeline->delete_from_client();
                // return $timeline;
            }
        }

        // delete reports for demand
        if ($demand->reports && $demand->reports->count() > 0) {
            foreach ($demand->reports as $report) {
                $report->delete();
            }
        }

        // delete files
        if ($demand->files && $demand->files->count() > 0) {

            foreach ($demand->files as $theFile) {
                $theFile->delete();
            }
        }

        if (!$demand->delete()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('demands.index')->with('success', 'Operatiune efectuata cu succes.');
    }

    public function destroy(Demand $demand)
    {
        // dd('hit');

        // if(!$demand->belongsToMe()){
        //     return redirect()->back();
        // }

        $this->authorize('update', $demand);

        if ($demand->winner && $demand->winner()->count() >= 1) {
            $demand->winner->delete();
        }

        if ($demand->quotes && $demand->quotes->count() > 0) {
            // dd($demand->quotes);
            // $demand->quotes()->delete();
            foreach ($demand->quotes as $quote) {

                if ($quote->files && $quote->files->count() > 0) {
                    foreach ($quote->files as $file) {
                        $pathToFile = public_path() . '/storage\/quotes\/' . $file->name;
                        if (file_exists($pathToFile)) {
                            unlink($pathToFile);
                        }

                        $file->delete();
                    }
                }

                $quote->delete();
            }
        }

        // delete demand's client_messages

        if ($demand->reports && $demand->reports->count() > 0) {
            foreach ($demand->reports as $report) {
                $report->delete();
            }
        }

        if ($demand->prospects && $demand->prospects()->count() > 0) {
            $demand->prospects()->delete();
        }

        // Delete timelines?

        // delete files
        if ($demand->files && $demand->files->count() > 0) {

            foreach ($demand->files as $theFile) {
                $theFile->delete();
            }
        }

        if (!$demand->delete()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('demands.index')->with('success', 'Operatiune efectuata cu succes.');
    }

    public function destroyUUID($uuid)
    {
        // dd('hit');

        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        if (!$demand->belongsToMe()) {
            return redirect()->back()->with('error', 'Nu puteti elimina aceasta cerere.');
        }

        if ($demand->quotes && $demand->quotes->count() > 0) {
            // dd($demand->quotes);
            // $demand->quotes()->delete();
            foreach ($demand->quotes as $quote) {

                if ($quote->files && $quote->files->count() > 0) {
                    foreach ($quote->files as $file) {
                        $pathToFile = public_path() . '/storage\/quotes\/' . $file->name;
                        if (file_exists($pathToFile)) {
                            unlink($pathToFile);
                        }

                        $file->delete();
                    }
                }

                $quote->delete();
            }
        }

        if ($demand->reports && $demand->reports->count() > 0) {
            foreach ($demand->reports as $report) {
                $report->delete();
            }
        }

        // Delete timelines?

        // delete files
        if ($demand->files && $demand->files->count() > 0) {

            foreach ($demand->files as $theFile) {
                // $pathToFile = public_path() . '/storage\/demands\/' . $theFile->name;
                // if (file_exists($pathToFile)) {
                //     unlink($pathToFile);
                // }

                $theFile->delete();
            }
        }

        if (!$demand->delete()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('demands.index')->with('success', 'Operatiune efectuata cu succes.');
    }

    public function buyDemand(Request $request, $id)
    {
        $demand = Demand::findOrFail($id);

        if ($demand->hasUser()) {
            if ($demand->user_id == auth()->user()->id) {
                return redirect()->back()->with('error', 'Actiune nepermisa.');
            }
        }

        // Verifica daca este deja cumparata / deblocata.
        if ($demand->hasProfessional(auth()->user()->professional)) {
            $existing_timeline = auth()->user()->professional->timelines()->where('demand_id', $demand->id)->first();
            return redirect()->route('timeline.show.pro', $existing_timeline->id);
            // return redirect()->back();
        }

        // Verifica daca utilizatorul are suficient credit.
        if (auth()->user()->getCreditAmount() < $demand->getCalculatedPrice()) {
            return redirect()->back()->with('error', 'Credit insuficient. Reincarcati contul pentru a putea debloca cereri.');
        }

        // Verifica daca numarul maxim de oferte permise a fost atins. (Cerere)
        if ($demand->getNumberBought() >= $demand->maximumOffers()) {
            if ($demand->getState() == '1') {
                $demand->state = '0';
                $demand->save();
            }

            return redirect()->back()->with('error', 'Nu puteti debloca cererea. Numarul maxim de oferte permise a fost atins.');
        }

        // CUmpara cerere. Scade din sold.
        auth()->user()->credit->substractAmount($demand->getCalculatedPrice());
        auth()->user()->credit->save();

        // Creeaza Timeline
        $timeline = Timeline::create([
            'demand_id' => $demand->id,
            'professional_id' => auth()->user()->professional->id,
            'user_id' => $demand->user_id,
            'uuid' => $this->generateTimelineUUID(),
        ]);

        // Inregistreaza activitate
        Activity::create([
            'user_id' => auth()->user()->id,
            'demand_id' => $demand->id,
            'description' => 'Deblocare cerere.',
            'amount' => $demand->getCalculatedPrice(),
        ]);

        // dd(auth()->user()->credit->amount);
        // deblocheaza date cerere
        $demand->professionals()->attach(auth()->user()->professional);

        // Notify the owner of the demand.
        Notification::send($demand->user, new DemandBought($demand));
        event(new \App\Events\BuyDemandEvent($demand, auth()->user()));

        return redirect()->route('timeline.show.pro', $timeline->id)->with('success', 'Cererea a fost delocata cu succes.');
        // return redirect()->route('demands.show', $demand)->with('success', 'Cererea a fost delocata cu succes.');
    }

    public function buyDemandUUID(Request $request, $uuid)
    {
        // $demand = Demand::findOrFail($id);
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        if ($demand->hasUser()) {
            if ($demand->user_id == auth()->user()->id) {
                return redirect()->back()->with('error', 'Actiune nepermisa.');
            }
        }

        // Verifica daca este deja cumparata / deblocata.
        if ($demand->hasProfessional(auth()->user()->professional)) {
            $existing_timeline = auth()->user()->professional->timelines()->where('demand_id', $demand->id)->first();
            return redirect()->route('timeline.show.pro', $existing_timeline->id);
            // return redirect()->back();
        }

        // Verifica daca utilizatorul are suficient credit.
        if (auth()->user()->getCreditAmount() < $demand->getCalculatedPrice()) {
            return redirect()->back()->with('error', 'Credit insuficient. Reincarcati contul pentru a putea debloca cereri.');
        }

        // CUmpara cerere. Scade din sold.
        auth()->user()->credit->substractAmount($demand->getCalculatedPrice());
        auth()->user()->credit->save();

        // Creeaza Timeline
        $timeline = Timeline::create([
            'demand_id' => $demand->id,
            'professional_id' => auth()->user()->professional->id,
            'user_id' => $demand->user_id,
            'uuid' => $this->generateTimelineUUID(),
        ]);

        // Inregistreaza activitate
        Activity::create([
            'user_id' => auth()->user()->id,
            'demand_id' => $demand->id,
            'description' => 'Deblocare cerere.',
            'amount' => $demand->getCalculatedPrice(),
        ]);

        // dd(auth()->user()->credit->amount);
        // deblocheaza date cerere
        $demand->professionals()->attach(auth()->user()->professional);

        // Notify the owner of the demand.
        Notification::send($demand->user, new DemandBought($demand));
        event(new \App\Events\BuyDemandEvent($demand, auth()->user()));

        return redirect()->route('timeline.show.pro.uuid', $timeline->uuid)->with('success', 'Cererea a fost deblocata cu succes.');
        // return redirect()->route('demands.show', $demand)->with('success', 'Cererea a fost delocata cu succes.');
    }

    public function changeStatus(Request $request, $id)
    {
        $demand = Demand::findOrFail($id);

        // dd($demand->detail);
        if ($demand->detail->status == '0') {
            $demand->detail->status = '1';
        } else {
            $demand->detail->status = '0';
        }

        if (!$demand->detail->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->back()->with('success', 'Actiune executata cu succes.');
    }

    public function changeStatusUUID(Request $request, $uuid)
    {
        // $demand = Demand::findOrFail($id);
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        // dd($demand->detail);
        if ($demand->detail->status == '0') {
            $demand->detail->status = '1';
        } else {
            $demand->detail->status = '0';
        }

        if (!$demand->detail->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->back()->with('success', 'Actiune executata cu succes.');
    }

    public function relaunch(Request $request, $id)
    {

        $demand = Demand::findOrFail($id);

        $this->authorize('update', $demand);

        if (!$demand->belongsToMe()) {
            return redirect()->back();
        }

        if ($demand->detail->status == '1') {
            $demand->detail->status = '0';
        }

        if ($demand->state == '0') {
            $demand->state = '1';
        }

        // Incrementeaza numarul de oferte necesare
        $demand->detail->offers += 2;

        if (!$demand->detail->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->back()->with('success', 'Actiune executata cu succes.');
    }

    public function relaunchUUID(Request $request, $uuid)
    {
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        $this->authorize('update', $demand);

        if (!$demand->belongsToMe()) {
            return redirect()->back();
        }

        // if Demand is Active (0) or Completed (1)
        if ($demand->detail->status == '1') {
            $demand->detail->status = '0';
        }

        // State 0 = inactiv. State 1 = activ.
        if ($demand->state == '0') {
            $demand->state = '1';
            $demand->save();
        }

        // Incrementeaza numarul de oferte necesare
        $demand->detail->offers += 2;

        if (!$demand->detail->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('demands.show.owner.uuid', $demand->uuid)->with('success', 'Actiune executata cu succes.');
    }

    public function relaunch_demand_new_winner(Request $request, $id)
    {

        $demand = Demand::findOrFail($id);

        $this->authorize('update', $demand);

        if (!$demand->belongsToMe()) {
            return redirect()->back();
        }

        if ($demand->detail->status == '1') {
            $demand->detail->status = '0';
        }

        if ($demand->state == '0') {
            $demand->state = '1';
        }

        if ($demand->hasWinner()) {
            $demand->winner->delete();
        }

        // refuza toate ofertele curente
        $demand->prospects()->update(['status' => '4']);

        // Incrementeaza numarul de oferte necesare
        $demand->detail->offers += 2;

        if (!$demand->detail->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->back()->with('success', 'Actiune executata cu succes.');
    }

    public function relaunch_demand_new_winnerUUID(Request $request, $uuid)
    {
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        $this->authorize('update', $demand);

        if (!$demand->belongsToMe()) {
            return redirect()->back();
        }

        if ($demand->detail->status == '1') {
            $demand->detail->status = '0';
        }

        if ($demand->state == '0') {
            $demand->state = '1';
        }

        if ($demand->hasWinner()) {
            $demand->winner->delete();
        }

        // Incrementeaza numarul de oferte necesare
        $demand->detail->offers += 2;

        if (!$demand->detail->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('demands.show.uuid', $demand->uuid)->with('success', 'Actiune executata cu succes.');
    }

    public function markAsProspect(Request $request, $demand_id, $professional_id, $timeline_id)
    {
        $demand = Demand::findOrFail($demand_id);
        $professional = Professional::findOrFail($professional_id);
        $timeline = Timeline::findOrFail($timeline_id);
        $user = auth()->user();

        // if(!$demand->belongsToMe()){
        //     return redirect()->back();
        // }

        $this->authorize('update', $demand);

        $prospect = new Prospect();
        $prospect->demand_id = $demand->id;
        $prospect->professional_id = $professional->id;
        $prospect->user_id = $user->id;
        $prospect->timeline_id = $timeline->id;

        if (!$prospect->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        // Notify PRO
        Notification::send($timeline->professional->user, new TimelineAction($timeline, $timeline->user, 'proposition'));
        event(new \App\Events\ClientProposalEvent($timeline, auth()->user()));

        return redirect()->back()->with('success', 'Actiune executata cu succes.');
    }

    public function markAsNewWinner(Request $request, $demand_id, $winner_id, $professional_id)
    {
        $demand = Demand::findOrFail($demand_id);
        $professional = Professional::findOrFail($professional_id);

        // if(!$demand->belongsToMe()){
        //     return redirect()->back();
        // }

        $this->authorize('update', $demand);

        $winner = Winner::findOrFail($winner_id);
        $winner->status = '0';
        $winner->professional_id = $professional->id;

        if (!$winner->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->back()->with('success', 'Actiune executata cu succes.');
    }

    public function proAcceptDemand(Request $request, $demand_id, $prospect_id)
    {
        $demand = Demand::findOrFail($demand_id);
        $prospect = Prospect::findOrFail($prospect_id);
        $timeline = $prospect->timeline;

        if (!$prospect) {
            return redirect()->back();
        }

        if ($demand->id !== $timeline->demand->id) {
            return redirect()->back();
        }

        $this->authorize('update_pro', $timeline);

        $prospect->status = '1';

        if (!$prospect->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        // Notify Owner of Demand
        Notification::send($timeline->user, new TimelineAction($timeline, $timeline->professional->user, 'accept'));
        event(new \App\Events\ProResponseProposalEvent($timeline, auth()->user(), 'accept'));

        return redirect()->back()->with('success', 'Actiune executata cu succes.');
    }

    public function proRefuseDemand(Request $request, $demand_id, $prospect_id)
    {
        $demand = Demand::findOrFail($demand_id);
        $prospect = Prospect::findOrFail($prospect_id);
        $timeline = $prospect->timeline;

        if (!$prospect) {
            return redirect()->back();
        }

        if ($demand->id !== $timeline->demand->id) {
            return redirect()->back();
        }

        $this->authorize('update_pro', $timeline);

        $prospect->status = '2';

        if (!$prospect->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        // Notify Owner of Demand
        Notification::send($timeline->user, new TimelineAction($timeline, $timeline->professional->user, 'refuse'));
        event(new \App\Events\ProResponseProposalEvent($timeline, auth()->user(), 'refuse'));

        return redirect()->back()->with('success', 'Actiune executata cu succes.');
    }

    public function confirmWinner(Request $request, $demand_id, $prospect_id, $professional_id)
    {
        # Clientul confirma castigatorul ofertei.
        # Mai multe firme pot accepta propunerea clientului de a fi 'Castigator'. Clientul confirma castigatorul unic al cererii.
        // dd('atins confirma');
        $demand = Demand::findOrFail($demand_id);
        $prospect = Prospect::findOrFail($prospect_id);
        $professional = Professional::findOrFail($professional_id);

        if (!$demand->belongsToMe()) {
            return redirect()->back();
        }

        $this->authorize('update_client', $prospect->timeline);

        // sterge winneri curenti
        Winner::where('demand_id', $demand->id)->delete();

        $winner = new Winner();
        $winner->demand_id = $demand->id;
        $winner->user_id = auth()->user()->id;
        $winner->professional_id = $professional->id;

        if (!$winner->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        $demand->prospects()->update(['status' => '4']);

        $prospect->status = '3';
        $prospect->save();

        // Notify PRO
        $timeline = $prospect->timeline;
        Notification::send($timeline->professional->user, new TimelineAction($timeline, $timeline->user, 'confirm_winner'));
        event(new \App\Events\ClientResponseToProEvent($timeline, auth()->user(), 'accept'));

        return redirect()->back()->with('success', 'Actiune executata cu succes.');

    }

    public function refuseWinner(Request $request, $demand_id, $prospect_id)
    {
        # Clientul confirma castigatorul ofertei.
        # Mai multe firme pot accepta propunerea clientului de a fi 'Castigator'. Clientul confirma castigatorul unic al cererii.
        // dd('atins refuza');
        $demand = Demand::findOrFail($demand_id);
        $prospect = Prospect::findOrFail($prospect_id);

        if (!$demand->belongsToMe()) {
            return redirect()->back();
        }

        $this->authorize('update_client', $prospect->timeline);

        $prospect->status = '4'; // status: 4 => refuzat de client

        if (!$prospect->save()) {
            return redirect()->back();
        }

        // Notify PRO
        $timeline = $prospect->timeline;
        Notification::send($timeline->professional->user, new TimelineAction($timeline, $timeline->user, 'refuse_winner'));
        event(new \App\Events\ClientResponseToProEvent($timeline, auth()->user(), 'refuse'));

        return redirect()->back()->with('success', 'Actiune executata cu succes.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        // dd(config('services.algolia.appId'));
        // dd(config('services.algolia.apiKey'));
        $categories = Category::all();
        return view('volgh.demands.register', compact('categories'));
    }

    public function registerVue()
    {
        // dd(config('services.algolia.appId'));
        // dd(config('services.algolia.apiKey'));
        $categories = Category::all();
        return view('volgh.demands.register-vue', compact('categories'));
    }

    public function changeState(Request $request, $id)
    {
        $demand = Demand::findOrFail($id);

        $this->authorize('update', $demand);

        if ($demand->getState() == 1) {
            $demand->state = 0;
        } else {
            $demand->state = 1;
        }

        if (!$demand->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show', $demand)->with('success', 'Actiune executata cu succes.');
    }

    public function changeStateUUID(Request $request, $uuid)
    {
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        $this->authorize('update', $demand);

        if ($demand->getState() == 1) {
            $demand->state = 0;
        } else {
            $demand->state = 1;
        }

        if (!$demand->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show.owner.uuid', $demand->uuid)->with('success', 'Actiune executata cu succes.');
    }

    public function changeStatusToVerified(Request $request, $id)
    {
        // Status = 1 ==> Verified, cererea nu este falsa.
        $demand = Demand::findOrFail($id);

        if ($demand->getStatus() !== 1) {
            $demand->status = 1;

            // Setare stare ca Activ?
        }

        // mark reports status to 1
        if ($demand->reports && $demand->reports->count() > 0) {
            foreach ($demand->reports as $report) {
                $report->status = 1;

                $report->save();

                // send notification to each user that reported the demand.
                $user = $report->user;
                Notification::send($user, new ResponseForReportedDemandNotification($demand, $report, auth()->user(), 'is_true'));
            }
        }

        if (!$demand->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show', $demand)->with('success', 'Actiune executata cu succes.');
    }

    public function changeStatusToVerifiedUUID(Request $request, $uuid)
    {
        // Status = 1 ==> Verified, cererea nu este falsa.
        // $demand = Demand::findOrFail($id);
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        if ($demand->getStatus() !== 1) {
            $demand->status = 1;

            // Setare stare ca Activ?
        }

        // mark reports status to 1
        if ($demand->reports && $demand->reports->count() > 0) {
            foreach ($demand->reports as $report) {
                $report->status = 1;

                $report->save();

                // send notification to each user that reported the demand.
                $user = $report->user;
                Notification::send($user, new ResponseForReportedDemandNotification($demand, $report, auth()->user(), 'is_true'));
            }
        }

        if (!$demand->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show.uuid', $demand->uuid)->with('success', 'Actiune executata cu succes.');
    }

    public function changeStatusToUnverified(Request $request, $id)
    {
        // Status = 0 ==> Neverificat, cererea nu este falsa. Status default este 0.
        $demand = Demand::findOrFail($id);

        if ($demand->getStatus() !== 0) {
            $demand->status = 0;
        }

        if (!$demand->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show', $demand)->with('success', 'Actiune executata cu succes.');
    }

    public function changeStatusToUnverifiedUUID(Request $request, $uuid)
    {
        // Status = 0 ==> Neverificat, cererea nu este falsa. Status default este 0.
        // $demand = Demand::findOrFail($id);
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        if ($demand->getStatus() !== 0) {
            $demand->status = 0;
        }

        if (!$demand->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show.uuid', $demand->uuid)->with('success', 'Actiune executata cu succes.');
    }

    public function changeStatusToFalse(Request $request, $id)
    {
        // Status = 2 ==> verificata, cererea este falsa.
        // Status = 0 => cererea nu este verificata, dar este ok.
        $demand = Demand::findOrFail($id);

        if ($demand->getStatus() !== 2) {
            $demand->status = 2;

            // Marcare inactiv
            $demand->state = 0;
        }

        // Give credit back to buyers.
        if ($demand->getNumberOfBuyers() > 0) {
            foreach ($demand->professionals as $pro) {
                $pro->user->credit->amount += $demand->getCalculatedPrice();

                // Save in DB
                $pro->user->credit->save();
            }
        }

        // mark reports status to 1
        if ($demand->reports && $demand->reports->count() > 0) {
            foreach ($demand->reports as $report) {
                $report->status = 1;

                $report->save();

                // send notification to each user that reported the demand.
                $user = $report->user;
                Notification::send($user, new ResponseForReportedDemandNotification($demand, $report, auth()->user(), 'is_false'));

            }
        }

        if (!$demand->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show', $demand)->with('success', 'Actiune executata cu succes.');
    }

    public function changeStatusToFalseUUID(Request $request, $uuid)
    {
        // Status = 2 ==> verificata, cererea este falsa.
        // Status = 0 => cererea nu este verificata, dar este ok.
        // $demand = Demand::findOrFail($id);
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back();
        }

        if ($demand->getStatus() !== 2) {
            $demand->status = 2;

            // Marcare inactiv
            $demand->state = 0;
        }

        // Give credit back to buyers.
        if ($demand->getNumberOfBuyers() > 0) {
            foreach ($demand->professionals as $pro) {
                $pro->user->credit->amount += $demand->getCalculatedPrice();

                // Save in DB
                $pro->user->credit->save();
            }
        }

        // mark reports status to 1
        if ($demand->reports && $demand->reports->count() > 0) {
            foreach ($demand->reports as $report) {
                $report->status = 1;

                $report->save();

                // send notification to each user that reported the demand.
                $user = $report->user;
                Notification::send($user, new ResponseForReportedDemandNotification($demand, $report, auth()->user(), 'is_false'));

            }
        }

        if (!$demand->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show.uuid', $demand->uuid)->with('success', 'Actiune executata cu succes.');
    }

    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while (\App\Demand::where('uuid', $id)->get()->count() > 0) {
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

    private function generateTimelineUUID()
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
