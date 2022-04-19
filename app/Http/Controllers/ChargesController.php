<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ChargesController extends Controller
{
    

    public function index()
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk'
          );


          $charges = $stripe->charges->all(['customer' => auth()->user()->stripe_id]);

        // dd(auth()->user()->stripe_id);
        // dd($charges);

        return view('volgh.charges.index', [
            'charges' => $charges->data
        ]);
    }

    public function show($charge_id)
    {

        $customer = User::where('stripe_id', auth()->user()->stripe_id)->first();

        if(!$customer){
            return redirect()->back();
        }

        $stripe = new \Stripe\StripeClient(
            'sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk'
          );

        $charge = $stripe->charges->retrieve(
            $charge_id,
            []
          );

        // dd($charge);
        
        $invoice = \App\Invoice::where('payment_intent_id', $charge->payment_intent)->first();
        // dd($invoice);

        return view('volgh.charges.show', [
            'charge' => $charge,
            'customer' => $customer,
            'invoice' => $invoice
        ]);
    }
}
