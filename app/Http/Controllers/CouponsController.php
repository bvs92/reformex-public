<?php

namespace App\Http\Controllers;

class CouponsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        return view('volgh.coupons.index');
    }

    public function personal()
    {
        if (!auth()->user()->isPro()) {
            return redirect()->back();
        }

        return view('volgh.coupons.personal');
    }

    public function requests()
    {
        if (!auth()->user()->isPro()) {
            return redirect()->back();
        }

        return view('volgh.coupons.coupons-requests');
    }

    public function show($uuid)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        $coupon = \App\Coupon::where('uuid', $uuid)->firstOrFail();

        if ($coupon->user_id) {
            $coupon->user;
            $coupon->user->makeHidden(['card_brand', 'stripe_id', 'card_last_four']);
        }

        return view('volgh.coupons.show', compact('coupon'));
    }

    public function show_pro($id)
    {
        $user = auth()->user();
        if (!$user->isPro()) {
            return redirect()->back();
        }

        $coupon = $user->coupons()->where('uuid', $id)->first();

        if (!$coupon) {
            return redirect()->back();
        }

        if ($coupon->user_id) {
            $coupon->user;
            $coupon->user->makeHidden(['card_brand', 'stripe_id', 'card_last_four']);
        }

        return view('volgh.coupons.show_pro', compact('coupon'));
    }

    // admin
    public function all_requests()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        return view('volgh.coupons.all-requests');
    }
}
