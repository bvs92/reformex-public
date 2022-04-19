<?php

namespace App\Http\Controllers;

use App\Category;

class PublicDemandsController extends Controller
{
    public function register()
    {
        $categories = Category::all();
        return view('volgh.demands.public.register', compact('categories'));
    }

    public function single($uuid, $unique)
    {
        $demand = \App\Demand::where('uuid', $uuid)->firstOrFail();
        $demand->detail;
        $demand->buyers;
        $demand->buyers = $demand->buyers->map(function ($item) {
            $item['complete_name'] = $item->user->getTheName();
            if ($item->user->user_name_profile) {
                $item['user_name'] = $item->user->user_name_profile->username;
            } else {
                $item['user_name'] = $item->user->username;
            }
            $item->makeHidden(['user']);
            return $item;
        });
        $demand->buyers->makeHidden(['amount_paid']);
        $demand->categories;
        $demand->categories->makeHidden(['price', 'pivot']);
        $demand->files;
        $demand->attachments;

        return view('volgh.demands.public.single', compact('demand', 'unique'));
    }
}
