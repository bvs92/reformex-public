<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $current_user = auth()->user();
        if ($current_user->isAdmin()) {
            $professionals = \App\Professional::latest()->take(20)->get();
            $professionals = $professionals->map(function ($item) {
                $item->user;
                return $item;
            });

            $pending_users = \App\User::where('status', 0)->has('Professional')->take(20)->get();

            $pending_users = $pending_users->filter(function ($item) use ($current_user) {
                if ($item->id !== $current_user->id) {
                    return $item;
                }
            });

            $pending_users->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);

            // dd($professionals);
            return view('volgh.dashboard.version_admin', ['professionals' => $professionals, 'pending_users' => $pending_users]);
        }

        if (auth()->user()->isPro()) {
            $demands = \App\Demand::latest()->take(30)->get();
            $demands = $demands->map(function ($item) {
                $item->categories;
                return $item;
            });

            $demands = $demands->filter(function ($item) {
                if ($item->isStateActive()) {
                    return $item;
                }
            });

            // check if demand is mine
            $demands = $demands->filter(function ($item) {
                // $item->only(['id', 'uuid', 'subject', 'created_at', 'status', 'state', 'city']);
                if (!$item->belongsToMe()) {
                    return $item;
                }
            });

            $demands = $demands->filter(function ($item) {
                if (!$item->hasBuyer(auth()->user())) {
                    return $item;
                }
            });

            // cereri deblocate
            $demands_unlocked = auth()->user()->demands_bought->count();

            // credit
            $credit = auth()->user()->getCreditAmount();

            // total cupoane
            $coupons = auth()->user()->coupons;
            $total_coupons = 0;

            foreach ($coupons as $coupon) {
                $total_coupons += $coupon->amount;
            }

            $total_coupons = $total_coupons / 100;

            // expenses
            $user_activity = auth()->user()->activities;
            $total_expenses = 0;
            foreach ($user_activity as $activity) {
                $total_expenses += $activity->amount;
            }
            $total_expenses = $total_expenses / 100;

            // dd($total_coupons);

            // dd($demands);
            return view('volgh.dashboard.version_pro', ['demands' => $demands, 'demands_unlocked' => $demands_unlocked, 'credit' => $credit, 'total_coupons' => $total_coupons, 'total_expenses' => $total_expenses]);
        } else {
            return view('volgh.dashboard.version_client');
        }
        // return view('home');
        // if(Gate::allows('is-one')){
        // }

        // return "gresit";

        // $post = \App\Post::latest()->first();

        // if(Gate::allows('can-update', $post)){
        //     return auth()->user()->posts;
        // } else {
        //     return "Nu";
        // }

    }
}
