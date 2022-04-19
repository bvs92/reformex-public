<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\ChangeStateDemandNotification;
use App\Notifications\DeleteDemandBuyerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function getDemands()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $demands = \App\Demand::all();

        return response()->json(['demands' => $demands, 'total' => $demands->count()]);
    }

    public function getDemand($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $demand = \App\Demand::find($id);

        if (!$demand) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        $demand['total_price'] = $demand->getCalculatedPrice() / 100;

        $demand->files;
        $demand->categories;
        $demand->categories->makeHidden(['pivot']);

        $demand['buyers'] = $demand->buyers->map(function ($item) {
            $item->user->makeHidden(['stripe_id', 'card_brand', 'card_last_four', 'company', 'professional']);
            $item->user['full_name'] = $item->user->getTheName();
            return $item->user;
        });

        $demand->reports = $demand->reports->map(function ($item) {
            $item['user_full_name'] = $item->user->getTheName();
            $item->makeHidden(['user']);
            return $item;
        });

        $demand->offers = $demand->detail->offers;

        $demand->makeHidden(['professionals', 'detail']);

        return response()->json(['demand' => $demand, 'success' => true]);
    }

    public function getReportedDemands()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $demands = \App\Demand::all();

        $demands = $demands->filter(function ($item) {
            return $item->reports->count() > 0;
        });

        return response()->json(['demands' => $demands, 'total' => $demands->count()]);
    }

    public function changeState($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $demand = \App\Demand::find($id);

        if (!$demand) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        if ($demand->state == 1) {
            $demand->state = 0;
        } else {
            $demand->state = 1;
        }

        if (!$demand->save()) {
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        Notification::route('mail', $demand->email)
            ->notify(new ChangeStateDemandNotification($demand));

        return response()->json(['success' => true, 'state' => $demand->state]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $demand = \App\Demand::find($id);

        if (!$demand) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'required|min:2|max:255',
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|min:2|max:255',
            'phone' => 'required|string',
            'message' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_demand = $validator->valid();

        $demand->subject = $validated_demand['subject'];
        $demand->name = $validated_demand['name'];
        $demand->email = $validated_demand['email'];
        $demand->phone = $validated_demand['phone'];
        $demand->message = $validated_demand['message'];

        if (!$demand->save()) {
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        $demand->files;
        $demand->makeVisible(['email', 'phone', 'message']);

        return response()->json(['success' => true, 'demand' => $demand]);
    }

    public function updateLocation(Request $request, $id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $demand = \App\Demand::find($id);

        if (!$demand) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        $validator = Validator::make($request->all(), [
            'city' => 'required|min:2',
            'administrative' => 'required|min:2',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $validated_demand = $validator->valid();

        $demand->city = $validated_demand['city'];
        $demand->administrative = $validated_demand['administrative'];
        $demand->lat = $validated_demand['lat'];
        $demand->lng = $validated_demand['lng'];

        if (!$demand->save()) {
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        return response()->json(['success' => true]);
    }

    public function updateCategories(Request $request, $id)
    {

        $validated_demand['categories'] = (explode(',', $request->categories));
        $request->categories = collect($validated_demand['categories'])->map(function ($item) {
            return (int) $item;
        });

        // return response()->json(['request' => $request->categories]);

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $demand = \App\Demand::find($id);

        foreach ($request->categories as $category_id) {
            $category = \App\Category::find($category_id);
            if (!$category) {
                return response()->json(['errors' => 'Am intampinat erori.']);
            }
        }

        // return response()->json(['categorii' => $request->categories, 'demand' => $demand->id]);

        // sync categories
        $demand->categories()->sync($request->categories);

        if (!$demand->save()) {
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        return response()->json(['success' => true]);
    }

    // buyers
    public function deleteBuyer($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $buyer = \App\Buyer::find($id);

        if (!$buyer) {
            return response()->json(['errors' => 'Cumparator indisponibil.']);
        }

        $demand = $buyer->demand;

        // return money
        $user = $buyer->user;
        if ($user->credit) {
            $user->credit->amount += $buyer->amount_paid;
            $user->credit->save();
        }

        // delete reports of buyer
        if ($user->reports && $user->reports->count() > 0) {
            $user->reports()->where('demand_id', $buyer->demand_id)->delete();
        }

        // delete buyer
        if (!$buyer->delete()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        // notify buyer via email
        Notification::send($user, new DeleteDemandBuyerNotification($demand));

        return response()->json(['success' => true]);
    }

    public function deleteDemand($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException$e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $demand = \App\Demand::find($id);

        if (!$demand) {
            return response()->json(['errors' => 'Cerere indisponibila.']);
        }

        DB::beginTransaction();
        // delete reports of demand admin/demands/{id}/delete
        if ($demand->reports && $demand->reports()->count() > 0) {
            if (!$demand->reports()->delete()) {
                DB::rollBack();
                return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
            }
        }

        // delete buyers
        if ($demand->buyers && $demand->buyers()->count() > 0) {
            if (!$demand->buyers()->delete()) {
                DB::rollBack();
                return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
            }
        }

        // delete demand details

        // delete demand \files
        if ($demand->files && $demand->files()->count() > 0) {
            // if (!$demand->files()->delete()) {
            //     return response()->json(['errors' => '3Am intampinat erori. Incercati mai tarziu.']);
            // }

            foreach ($demand->files as $file) {
                $file->delete();
            }
        }

        if ($demand->attachments && $demand->attachments()->count() > 0) {
            // if (!$demand->files()->delete()) {
            //     return response()->json(['errors' => '3Am intampinat erori. Incercati mai tarziu.']);
            // }

            foreach ($demand->attachments as $file) {
                $pathToFile = 'uploads/demands/' . $file->name;
                if (Storage::disk('do_spaces')->exists($pathToFile)) {
                    Storage::disk('do_spaces')->delete($pathToFile);
                }
                $file->delete();
            }
        }

        // detach categories
        if (!$demand->categories()->detach()) {
            DB::rollBack();
        }

        if ($demand->detail) {
            if (!$demand->detail()->delete()) {
                DB::rollBack();
            }
        }

        // delete demand
        if (!$demand->delete()) {
            DB::rollBack();
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        // notify owner via email?
        // Notification::send($user, new DeleteDemandBuyerNotification($demand));
        DB::commit();
        return response()->json(['success' => true]);
    }
}
