<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function getDemands()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with(['error' => 'Nu aveti permisiunea de a accesa sectiunea.']);
        }

        return view('volgh.demands.admin.list-all');
    }

    public function getReportedDemands()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with(['error' => 'Nu aveti permisiunea de a accesa sectiunea.']);
        }

        return view('volgh.demands.admin.list-reported');
    }

    public function showDemand($uuid)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with(['error' => 'Nu aveti permisiunea de a accesa sectiunea.']);
        }

        $demand = \App\Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return redirect()->back()->with(['error' => 'Cererea nu exista.']);
        }

        $demand['total_price'] = $demand->getCalculatedPrice() / 100;

        $demand->files;
        $demand->attachments;
        // $demand['buyers'] = $demand->professionals->map(function ($item) {
        //     $item->user->makeHidden(['stripe_id', 'card_brand', 'card_last_four', 'company', 'professional']);
        //     $item->user['details'] = $item->pivot;
        //     $item->user['full_name'] = $item->user->getTheName();
        //     return $item->user;
        // });
        $demand->categories;
        $demand->categories->makeHidden(['pivot']);

        $demand['buyers'] = $demand->buyers->map(function ($item) {
            $item->user->makeHidden(['stripe_id', 'card_brand', 'card_last_four', 'company', 'professional']);
            // $item->user['details'] = $item;
            $item->user['full_name'] = $item->user->getTheName();
            $item->user->user_name_profile;
            return $item->user;
        });

        $demand->reports = $demand->reports->map(function ($item) {
            $item['user_full_name'] = $item->user->getTheName();
            $item->makeHidden(['user']);
            return $item;
        });

        $demand->offers = $demand->detail->offers;

        $demand->makeHidden(['professionals', 'detail']);

        return view('volgh.demands.admin.show-demand', compact('demand'));
    }
}
