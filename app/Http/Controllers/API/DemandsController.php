<?php

namespace App\Http\Controllers\API;

use App\Activity;
use App\Demand;
use App\DemandFile;
use App\Http\Controllers\Controller;
use App\Notifications\demands\DemandReportNotification;
use App\Notifications\RelaunchDemandNotification;
use App\Notifications\ReportDemandNotification;
use App\Notifications\ResponseForReportedDemandNotification;
use App\Utility\DictionaryRegex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DemandsController extends Controller
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

    public function search(Request $request)
    {
        return response()->json($request->all());
    }

    public function explore(Request $request)
    {
        // return response()->json(['request' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'location' => 'required',
            'range' => 'required|min:1|max:2000',
            'categories' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();
        // return response()->json(['request' => $valid_request['range']]);

        // validate input

        $location = $valid_request['location'];
        // return response()->json(['location' => $location['latlng']['lat']]);

        $lat = floatval($location['latlng']['lat']);
        $lng = floatval($location['latlng']['lng']);

        // $query = $location['name'];
        // // return Demand::search($query)->get();
        $result = Demand::search('')->with([
            'aroundLatLng' => $lat . ',' . $lng,
            'aroundRadius' => intval($valid_request['range']) * 1000,
            // 'aroundRadius' => 'all',
            'minimumAroundRadius' => 10000, // 10 km
            'aroundPrecision' => 2000,
            // 'getRankingInfo' => true,
            // 'filters' => "categories:" . $filter
        ])->get();

        // check if demand is active
        $result = $result->filter(function ($item) {
            if ($item->isStateActive()) {
                return $item;
            }
        });

        // check if demand is mine
        $result = $result->filter(function ($item) {
            if (!$item->belongsToMe()) {
                return $item;
            }
        });

        // if (auth()->user()->isPro()) {
        //     $pro = auth()->user()->professional;
        //     //Check if any demand is bought
        //     $result = $result->filter(function ($item) use ($pro) {
        //         // Log::info("PRO ID AICI ESTE: " . $pro->id);
        //         if (!$item->isBought($pro->id)) {
        //             return $item;
        //         }
        //     });
        // }

        $result = $result->filter(function ($item) {
            if (!$item->hasBuyer(auth()->user())) {
                return $item;
            }
        });

        // Filtreaza categoriile setate
        // $user_categories = (explode(',', $valid_request['categories']));
        $user_categories = $valid_request['categories'];

        $result = $result->filter(function ($item) use ($user_categories) {
            // dd($categories);
            foreach ($item->categories as $cat) {
                if (in_array($cat->id, $user_categories)) {
                    return $item;
                }
            }

            // dd($item->categories);
        });

        // $result->makeHidden(['phone', 'email', 'message']);
        // $result = $result->map(function ($item) {
        //     $item->makeHidden(['phone', 'email', 'user', 'message', 'name']);
        //     return $item;
        // });

        $result = $result->map(function ($item) {
            $item->makeHidden(['phone', 'email', 'user', 'message', 'name', 'categories']);
            $item->subject = DictionaryRegex::mask($item->subject);
            $item['categories_ids'] = $item->categories->map(function ($elem) {
                return $elem->id;
            });

            $item['categories_names'] = $item->categories->map(function ($elem) {
                return $elem->name;
            });

            return $item;
        });

        return response()->json(['demands' => $result]);
    }

    public function exploreAll()
    {
        // return response()->json(['request' => $request->all()]);

        // $result = Demand::search('')->get();
        // $result = Demand::all();
        $result = Demand::orderBy('created_at', 'desc')->get();

        // return response()->json(['demands' => $result]);

        // check if demand is active
        $result = $result->filter(function ($item) {
            if ($item->isStateActive()) {
                return $item;
            }
        });

        // check if demand is mine
        $result = $result->filter(function ($item) {
            $item->only(['id', 'uuid', 'subject', 'created_at', 'status', 'state', 'city']);
            if (!$item->belongsToMe()) {
                return $item;
            }
        });

        // if (auth()->user()->isPro()) {
        //     $pro = auth()->user()->professional;
        //     //Check if any demand is bought
        //     $result = $result->filter(function ($item) use ($pro) {
        //         // Log::info("PRO ID AICI ESTE: " . $pro->id);
        //         if (!$item->isBought($pro->id)) {
        //             return $item;
        //         }
        //     });
        // }

        $result = $result->filter(function ($item) {
            if (!$item->hasBuyer(auth()->user())) {
                return $item;
            }
        });

        $result = $result->map(function ($item) {
            $item->makeHidden(['phone', 'email', 'user', 'message', 'name', 'categories']);
            $item->subject = DictionaryRegex::mask($item->subject);
            $item['categories_ids'] = $item->categories->map(function ($elem) {
                return $elem->id;
            });

            $item['categories_names'] = $item->categories->map(function ($elem) {
                return $elem->name;
            });

            return $item;
        });

        // $result->makeHidden(['phone', 'email', 'message']);
        // $result->only(['id', 'uuid', 'subject', 'created_at', 'status', 'state', 'city']);

        return response()->json(['demands' => $result]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required|min:2|max:255',
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|min:2|max:255',
            'phone' => 'required|string',
            'city' => 'required|min:2|max:255',
            'message' => 'required|min:10',
            'categories' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_demand = $validator->valid();

        $validated_demand['user_id'] = auth()->user()->id; // nullable field

        $validated_demand['lat'] = $request->input('lat');
        $validated_demand['lng'] = $request->input('lng');
        $validated_demand['administrative'] = $request->input('administrative');

        // $validated_categories = request()->validate(['categories' => 'required|exists:categories,id']); // coming
        // $validated_categories = ['1'];

        // return response()->json($validated_demand['administrative']);

        // dd($validated_categories['categories']);

        $validated_demand['uuid'] = $this->generateUUID();

        $validated_demand['categories'] = (explode(',', $validated_demand['categories']));
        $final_categories = collect($validated_demand['categories'])->map(function ($item) {
            return (int) $item;
        });

        if ($final_categories->count() < 1) {
            return response()->json(['result' => false]);
        }

        // return response()->json($validated_demand['categories']);
        // return response()->json($final_categories);

        if (!$demand = Demand::create($validated_demand)) {
            return response()->json(['result' => false]);
        }

        if ($request->hasFile('the_files')) {
            $all_files = $request->file('the_files');
            foreach ($all_files as $one_file) {
                $infos = $this->uploadFile($one_file);

                $demandFile = new DemandFile();
                $demandFile->demand_id = $demand->id;
                $demandFile->user_id = auth()->user() ? auth()->user()->id : null;
                $demandFile->name = $infos['file_name'];
                $demandFile->extension = $infos['ext'];
                // $demandFile->path = null;
                $demandFile->mime_type = $infos['mime_type'];

                $demandFile->save();
            }

            // $quote['files'] = $quote->files;
        }

        $demand->detail()->create();

        $demand->categories()->sync(json_decode($final_categories));

        return response()->json(['result' => true]);

        // return response('ok - response');
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

    public function personal()
    {
        $demands = auth()->user()->demands;

        return response()->json(['demands' => $demands]);
    }

    // get associated review

    public function get_review($demand_id)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json($user);

        $demand = Demand::find($demand_id);

        if (!$demand) {
            return response()->json(['errors' => 'Nu am gasit nimic.']);
        }

        // check if current user is authorized to view the demand.
        if (!$user->can('update', $demand)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        return response()->json(['review' => $demand->review]);

    }

    public function get_active_winner($demand_id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json($user);

        $demand = Demand::find($demand_id);

        if (!$demand) {
            return response()->json(['errors' => 'Nu am gasit nimic.']);
        }

        $winner = $demand->hasActiveWinner() ? $demand->getWinner() : null;

        if ($winner) {
            $winner['name'] = $winner->professional->getName();
            $winner['demand_uuid'] = $winner->demand->uuid;
            $winner['company_name'] = $winner->professional->getName();
            $winner['email'] = $winner->professional->user->email;
        }

        return response()->json(['active_winner' => $winner]);

    }

    protected function uploadFile($file)
    {

        // Get image original extension
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $random = substr(md5(mt_rand()), 0, 5);

        // dd($mimeType);

        // Compose the name
        $file_name = 'demand-file-' . time() . "-" . $random . '.' . $ext;

        $infos = [
            'ext' => $ext,
            'mime_type' => $mimeType,
            'file_name' => $file_name,
        ];

        // Save file
        // Image::make($file)
        // ->resize(300, 300, function ($constraint) {
        //     $constraint->aspectRatio();
        // })
        // ->save(public_path('images/avatars/' . $file_name));

        // Register file in Files table.

        Storage::disk('public')->putFileAs('demands', $file, $file_name);

        return $infos;
    }

    public function markAsInvalid(Request $request, $uuid)
    {

        try {
            $userAdmin = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$userAdmin->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        // Status = 2 ==> verificata, cererea este falsa.
        // Status = 0 => cererea nu este verificata, dar este ok.
        // $demand = Demand::findOrFail($id);
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        if ($demand->getStatus() !== 2) {
            $demand->status = 2;

            // Marcare inactiv
            $demand->state = 0;
        }

        DB::beginTransaction();

        // Give credit back to buyers.
        if ($demand->buyers()->count() > 0) {
            foreach ($demand->buyers as $buyer) {
                $buyer->user->credit->amount += $buyer->amount_paid;

                // Save in DB
                if (!$buyer->user->credit->save()) {
                    DB::rollBack();
                }
            }
        }

        // mark reports status to 1
        if ($demand->reports && $demand->reports->count() > 0) {
            if (!$demand->reports()->update(['status' => 1])) {
                DB::rollBack();
            }

            foreach ($demand->reports as $report) {
                // send notification to each user that reported the demand.
                Notification::send($report->user, new ResponseForReportedDemandNotification($demand, $report, auth()->user(), 'is_false'));

            }
        }

        if (!$demand->save()) {
            DB::rollBack();
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        DB::commit();

        return response()->json(['success' => true]);
    }

    public function markAsValid(Request $request, $uuid)
    {
        try {
            $userAdmin = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$userAdmin->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        // Status = 1 ==> Verified, cererea nu este falsa.
        // $demand = Demand::findOrFail($id);
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        if ($demand->getStatus() !== 1) {
            $demand->status = 1;

            // Setare stare ca Activ?
        }

        if ($demand->state !== 1) {
            $demand->state = 1;
        }

        // mark reports status to 1
        if ($demand->reports && $demand->reports->count() > 0) {
            $demand->reports()->update(['status' => 2]); // report->status = 2 => demand is valid

            foreach ($demand->reports as $report) {
                // send notification to each user that reported the demand.
                Notification::send($report->user, new ResponseForReportedDemandNotification($demand, $report, auth()->user(), 'is_true'));
            }
        }

        if (!$demand->save()) {
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        return response()->json(['success' => true]);
    }

    /**
     * mark demand as Valid and take back the money from users.
     * folosit dupa ce am marcat 'din greseala' cererea ca inactiva.
     */
    public function remarkAsValid(Request $request, $uuid)
    {
        try {
            $userAdmin = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$userAdmin->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        // Status = 1 ==> Verified, cererea nu este falsa.
        // $demand = Demand::findOrFail($id);
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        if ($demand->getStatus() !== 1) {
            $demand->status = 1;

            // Setare stare ca Activ?
        }

        if ($demand->state !== 1) {
            $demand->state = 1;
        }

        DB::beginTransaction();

        // Give credit back to buyers.
        if ($demand->buyers()->count() > 0) {
            foreach ($demand->buyers as $buyer) {
                if ($buyer->user->credit->amount >= $buyer->amount_paid) {
                    $buyer->user->credit->amount -= $buyer->amount_paid;

                    // Save in DB
                    if (!$buyer->user->credit->save()) {
                        DB::rollBack();
                    }
                }
            }
        }

        // mark reports status to 1
        if ($demand->reports && $demand->reports->count() > 0) {
            if (!$demand->reports()->update(['status' => 2])) {
                DB::rollBack();
            }

            foreach ($demand->reports as $report) {
                // send notification to each user that reported the demand.
                Notification::send($report->user, new ResponseForReportedDemandNotification($demand, $report, auth()->user(), 'is_true'));
            }
        }

        if (!$demand->save()) {
            DB::rollBack();
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        DB::commit();

        return response()->json(['success' => true]);
    }

    public function relaunchByAdmin(Request $request, $uuid)
    {
        try {
            $userAdmin = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$userAdmin->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        // Status = 1 ==> Verified, cererea nu este falsa.
        // $demand = Demand::findOrFail($id);
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        $demand->detail->offers += 3;

        if (!$demand->detail->save()) {
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        // setare ca activa
        if ($demand->state !== 1) {
            $demand->state = 1;
            $demand->save();
        }

        // notificare owner.
        Notification::route('mail', $demand->email)
            ->notify(new RelaunchDemandNotification($demand));

        return response()->json(['success' => true]);
    }

    // Vue - Professional

    public function getReportedDemands()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $reports = $user->reports;

        $demands = $reports->map(function ($item) {
            $item->makeHidden(['phone', 'message']);
            return $item->demand;
        });

        return response()->json(['demands' => $demands, 'total' => $demands->count()]);
    }

    public function getUnlockedDemands()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $reports = $user->demands_bought;

        $demands = $reports->map(function ($item) {
            $item->makeHidden(['phone', 'message']);
            return $item->demand;
        });

        return response()->json(['demands' => $demands, 'total' => $demands->count()]);
    }

    public function unlockDemandVue($uuid)
    {
        $demand = \App\Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return response()->json(['errors' => 'Cerere indisponibila.']);
        }

        if (!auth()->user()->isPro()) {
            return response()->json(['errors' => 'Nu aveti access la aceasta sectiune.']);
        }

        if ($demand->hasBuyer(auth()->user())) {
            return response()->json(['errors' => 'Cererea este deja deblocata.']);
        }

        // register buyer

        $user = auth()->user();

        if (!$user->credit) {
            return response()->json(['errors' => 'Eroare credit.']);
        }

        if ($user->credit->amount < $demand->getCalculatedPrice()) {
            return response()->json(['credit_errors' => 'Credit insuficient.']);
        }

        $buyer = new \App\Buyer();
        $buyer->demand_id = $demand->id;
        $buyer->user_id = auth()->user()->id;
        $buyer->amount_paid = $demand->getCalculatedPrice(); //

        DB::beginTransaction();

        if (!$buyer->save()) {
            DB::rollBack();
            return response()->json(['errors' => 'Am intampiant erori. Incercati mai tarziu.']);
        }

        // substract money amount from user credit

        $user->credit->amount -= $demand->getCalculatedPrice();

        if (!$user->credit->save()) {
            DB::rollBack();
            return response()->json(['errors' => 'Am intampiant erori. Incercati mai tarziu.']);
        }

        DB::commit();

        // Inregistreaza activitate
        Activity::create([
            'user_id' => $user->id,
            'demand_id' => $demand->id,
            'description' => 'Deblocare cerere.',
            'amount' => $demand->getCalculatedPrice(),
        ]);

        if ($demand->buyers() && $demand->buyers()->count() >= $demand->maximumOffers()) {
            // if (true) {
            $the_buyers = $demand->buyers->map(function ($item) {
                $item->user->makeHidden(['password', 'remember_token', 'stripe_id', 'card_brand', 'card_last_four']);
                $item['complete_name'] = $item->user->getTheName();
                $item->makeHidden(['professional']);
                return $item->user;
            });
            Notification::route('mail', $demand->email)->notify(new DemandReportNotification($the_buyers, $demand));

            \App\Demand::where('id', $demand->id)->update(['state' => 0]);
        }

        $demand['is_bought'] = $demand->hasBuyer($user);

        $demand['report'] = $demand->reports()->where('user_id', auth()->user()->id)->first();

        $demand->categories->makeHidden(['price', 'description', 'pivot']);
        $demand['categories'] = $demand->categories;
        $demand->files;
        $demand->attachments;

        return response()->json(['success' => true, 'demand' => $demand]);
    }

    public function reportDemandVue(Request $request, $id)
    {
        $demand = \App\Demand::where('id', $id)->first();

        if (!$demand) {
            return response()->json(['errors' => 'Cerere indisponibila.']);
        }

        if (!auth()->user()->isPro()) {
            return response()->json(['errors' => 'Nu aveti access la aceasta sectiune.']);
        }

        if (!$demand->hasBuyer(auth()->user())) {
            return response()->json(['errors' => 'Nu aveti access la aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_report = $validator->valid();

        // register report
        $report = new \App\DemandReport();
        $report->message = $valid_report['message'];
        $report->demand_id = $demand->id;
        $report->user_id = auth()->user()->id;
        $report->status = 0;

        if (!$report->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        $demand['is_bought'] = $demand->hasBuyer(auth()->user());

        $demand['report'] = $demand->reports()->where('user_id', auth()->user()->id)->first();

        $demand->categories->makeHidden(['price', 'description', 'pivot']);
        $demand['categories'] = $demand->categories;

        // notify admin via email & database
        $admins = \App\User::role('admin')->get();
        Notification::send($admins, new ReportDemandNotification(auth()->user(), $demand));

        return response()->json(['success' => true, 'demand' => $demand]);
    }
}
