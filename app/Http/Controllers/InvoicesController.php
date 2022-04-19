<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\URL;

class InvoicesController extends Controller
{
    

    public function index()
    {
        if(!auth()->user()->isAdmin()){
            return redirect()->back();
        }

        return view('volgh.invoices.index');
    }


    public function show($id, $stripe_id)
    {
        $customer = User::where('stripe_id', $stripe_id)->first();

        $invoice = \App\Invoice::where('uuid', $id)->first();


        $stripe = new \Stripe\StripeClient(
            config('services.stripe.stripe_secret')
          );
        
          $paymentIntent = $stripe->paymentIntents->retrieve(
            $invoice->payment_intent_id,
            []
          );

        //   dd($paymentIntent );

        // dd($invoice);

        return view('volgh.invoices.first', [
            'id'    => $invoice->uuid,
            'name' => $customer->last_name . ' ' . $customer->first_name,
            'email' => $customer->email,
            'created' => $invoice->created_at,
            'amount' => $invoice->amount / 100
        ]);
    }


    public function show_charge($id, $charge_id)
    {

        $customer = User::where('stripe_id', auth()->user()->stripe_id)->first();

        $stripe = new \Stripe\StripeClient(
            'sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk'
          );

        $charge = $stripe->charges->retrieve(
            $charge_id,
            []
          );

        $invoice = \App\Invoice::where('uuid', $id)->first();

        // dd($charge);

        return view('volgh.invoices.first', [
            'id'    => $invoice->uuid,
            'name' => $customer->getName(),
            'email' => $customer->email,
            'company_name' => $customer->isPro() ? $customer->getCompanyName() : null,
            'amount' => $charge->amount / 100,
            'receipt_id' => $charge->receipt_number,
            'created' => $charge->created,
            'charge_id' => $charge->id
        ]);
    }


    public function show_invoice($invoice_id)
    {

        // $customer = User::where('stripe_id', $stripe_id)->first();

        $stripe = new \Stripe\StripeClient(
            'sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk'
          );

        $invoice = $stripe->invoices->retrieve(
            $invoice_id,
            []
          );

        // dd($invoice);


        // $customer = User::where('stripe_id', $invoice->customer)->first();

        // dd($customer);

        return view('volgh.invoices.first', [
            'id'    => $invoice->number,
            'name' => $invoice->customer_name,
            'email' => $invoice->customer_email,
            'amount' => $invoice->amount_paid / 100,
            'receipt_id' => $invoice->receipt_number,
            'created' => $invoice->created
        ]);
    }

    public function download($id)
    {

        $customer = User::where('stripe_id', auth()->user()->stripe_id)->first();

        $invoice = \App\Invoice::where('uuid', $id)->first();



        $data = [
            'id'    => $invoice->uuid,
            'logo_image' => URL::asset('assets/images/brand/reformex-logo.png'),
            'name' => $customer->last_name . ' ' . $customer->first_name,
            'email' => $customer->email,
            'created' => $invoice->created_at,
            'amount' => $invoice->amount / 100
        ];
        $pdf = PDF::loadView('volgh.invoices.fiscal-pdf', $data);
        // $name = Str::uuid();
        $name = 'factura-' . $invoice->uuid;
        return $pdf->download($name . '.pdf');
    }


    public function download_charge($id, $charge_id)
    {

        $customer = User::where('stripe_id', auth()->user()->stripe_id)->first();

        $invoice = \App\Invoice::where('uuid', $id)->first();

        $stripe = new \Stripe\StripeClient(
            'sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk'
          );

        $charge = $stripe->charges->retrieve(
            $charge_id,
            []
          );



        $data = [
            'id'    => $invoice->uuid,
            'logo_image' => URL::asset('assets/images/brand/reformex-logo.png'),
            'name' => $customer->last_name . ' ' . $customer->first_name,
            'email' => $customer->email,
            'created' => $invoice->created_at,
            'amount' => $invoice->amount / 100,
            'receipt_id' => $charge->receipt_number
        ];
        $pdf = PDF::loadView('volgh.invoices.fiscal-pdf', $data);
        // $name = Str::uuid();
        $name = 'factura-' . $invoice->uuid;
        return $pdf->download($name . '.pdf');
    }


    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while(\App\Invoice::where('uuid', $id)->get()->count() > 0){
            // regenereaza daca exista
            $res = \Illuminate\Support\Str::uuid();
            // $res = rand(0, 99);
            $id = substr($res, 0, 8);
            // echo $id . '<br/>';
        }

        // echo 'JOS: ' . $id . '<br/>';
        return strtoupper($id);

        // => id este unic si poate fi atasat unei cereri.
    }

}
