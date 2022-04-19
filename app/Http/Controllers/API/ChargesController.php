<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class ChargesController extends Controller
{
    public function index()
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

        $charges = $stripe->charges->all(['customer' => auth()->user()->stripe_id]);

        // dd(auth()->user()->stripe_id);
        // dd($charges);

        // return view('volgh.charges.index', [
        //     'charges' => $charges->data,
        // ]);
    }

    public function show($charge_id)
    {

        $customer = User::where('stripe_id', auth()->user()->stripe_id)->first();

        if (!$customer) {
            return redirect()->back();
        }

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

        $charge = $stripe->charges->retrieve(
            $charge_id,
            []
        );

        // dd($charge);

        $invoice = \App\Invoice::where('payment_intent_id', $charge->payment_intent)->first();
        // dd($invoice);

        // return view('volgh.charges.show', [
        //     'charge' => $charge,
        //     'customer' => $customer,
        //     'invoice' => $invoice,
        // ]);
    }

    public function userCharges($id)
    {

        if (!auth()->user()->isAdmin()) {
            return resposne()->json(['errors' => true]);
        }

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

        $user = \App\User::find($id);

        $charges = $stripe->charges->all(['customer' => $user->stripe_id]);

        $charges = collect($charges->data)->map(function ($item) {
            $item->created = \Carbon\Carbon::createFromTimestamp($item->created);
            return $item;
        });

        return response()->json(['charges' => $charges, 'total' => $charges->count()]);
    }

    public function personal()
    {
        $user = auth()->user();
        if (!$user || !$user->isPro()) {
            return resposne()->json(['errors' => true]);
        }

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

        $charges = $stripe->charges->all(['customer' => $user->stripe_id]);

        $charges = collect($charges->data)->map(function ($item) {
            $item->created = \Carbon\Carbon::createFromTimestamp($item->created);
            return $item;
        });

        return response()->json(['charges' => $charges, 'total' => $charges->count()]);
    }

    public function userLastPayment($id)
    {

        if (!auth()->user()->isAdmin()) {
            return resposne()->json(['errors' => true]);
        }

        $user = \App\User::find($id);

        $last_payment = $user->payments ? $user->payments()->latest()->first() : null;

        return response()->json(['last_payment' => $last_payment]);
    }
}
