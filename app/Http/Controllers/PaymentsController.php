<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Invoice;
use App\Payment;
use App\RefundsDemand;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Laravel\Cashier\Cashier;

class PaymentsController extends Controller
{

    public function download($uuid)
    {

        $customer = auth()->user();

        $payment = Payment::where('uuid', $uuid)->first();

        $data = [
            'id' => $payment->uuid,
            'logo_image' => URL::asset('assets/images/brand/reformex-logo.png'),
            'name' => $customer->last_name . ' ' . $customer->first_name,
            'email' => $customer->email,
            'created' => $payment->created_at,
            'amount' => $payment->amount_total / 100,
        ];
        $pdf = PDF::loadView('volgh.invoices.fiscal-pdf', $data);
        // $name = Str::uuid();
        $name = 'factura-' . $payment->uuid;
        return $pdf->download($name . '.pdf');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = auth()->user()->payments;

        return view('volgh.payments.index', [
            'payments' => $payments,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        return view('volgh.payments.index-admin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        return view('volgh.payments.show')->with(['uuid' => $uuid]);
    }

    public function show_payment($id)
    {
        // dd('aici');
        $stripe = new \Stripe\StripeClient(
            config('services.stripe.stripe_secret')
        );

        $paymentIntent = $stripe->paymentIntents->retrieve(
            $id,
            []
        );

        //   dd($paymentIntent);

        if ($paymentIntent->payment_method) {
            $payment_method = $stripe->paymentMethods->retrieve(
                $paymentIntent->payment_method,
                []
            );
        } else {
            $payment_method = null;
        }

        $customer = User::where('stripe_id', $paymentIntent->customer)->first();
        //   $customer = User::where('stripe_id', 'cus_IbyXsfj8NfZgwn')->first();

        //   dd($customer);

        $invoices = \App\Invoice::where('user_id', auth()->user()->id)->where('payment_intent_id', $paymentIntent->id)->get();

        $refunds_demands = RefundsDemand::where('payment_intent_id', $paymentIntent->id)->get();

        //   return $paymentIntent->charges->data[0]->amount_refunded;

        // return $payment_method;

        return view('volgh.payments.view-payment', [
            'payment' => $paymentIntent,
            'payment_method' => $payment_method,
            'customer' => $customer,
            'refunds_demands' => $refunds_demands,
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

    public function singleView()
    {
        // $available_prices = ['price_1GsxTNI0uyZGzh5L5uR0UPas' => 'Monthly'];

        // For saving the card
        \Stripe\Stripe::setApiKey('sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk');

        $intent = \Stripe\SetupIntent::create([
            'customer' => auth()->user()->stripe_id,
        ]);

        # end for saving the card

        $existing_methods = \Stripe\PaymentMethod::all([
            'customer' => $intent->customer,
            'type' => 'card',
        ]);

        return view('volgh.payments.single', [
            // 'available_plans' => $available_plans
            'intent' => $intent,
            'existing_methods' => $existing_methods,
        ]);
    }

    public function singleViewLater()
    {
        // $available_prices = ['price_1GsxTNI0uyZGzh5L5uR0UPas' => 'Monthly'];

        // For saving the card
        \Stripe\Stripe::setApiKey('sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk');

        $intent = \Stripe\PaymentIntent::create([
            'amount' => 1099,
            'currency' => 'ron',
            'customer' => auth()->user()->stripe_id,
        ]);

        # end for saving the card

        $existing_methods = \Stripe\PaymentMethod::all([
            // 'customer' => 'cus_HiyRIwhnHPuXwq',
            'customer' => $intent->customer,
            'type' => 'card',
        ]);

        return view('volgh.payments.single-later', [
            // 'available_plans' => $available_plans
            'intent' => $intent,
            'existing_methods' => $existing_methods,
        ]);
    }

    public function vue()
    {
        // $available_prices = ['price_1GsxTNI0uyZGzh5L5uR0UPas' => 'Monthly'];

        // For saving the card
        \Stripe\Stripe::setApiKey('sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk');

        $intent = \Stripe\SetupIntent::create([
            'customer' => auth()->user()->stripe_id,
        ]);

        # end for saving the card

        // dd($intent->client_secret);

        // for future: check if $intent->customer exists
        $existing_methods = \Stripe\PaymentMethod::all([
            // 'customer' => 'cus_HiyRIwhnHPuXwq',
            'customer' => $intent->customer,
            'type' => 'card',
        ]);

        //   dd($intent);
        //   dd($existing_methods->data);

        return view('volgh.payments.vue', [
            // 'available_plans' => $available_plans
            'intent' => $intent,
            'existing_methods' => $existing_methods,
        ]);
    }

    public function singleCheckout(Request $request)
    {

        // dd('aiici');
        // return response()->json($request->amount);
        // $planId = $request->plan;

        // auth()->user()->newSubscription('default', $planId)->create($request->payment_method);
        // return response()->json($request->paymentMethod);
        // $credit = 'Lei' + 150 + '.00 ron';

        // $credit = 10000; // 100 unitati (RON)

        // return response()->json(['payload' => $request->payload]);

        if (!auth()->user()->credit) {
            Credit::create([
                'user_id' => auth()->user()->id,
                'amount' => 0,
            ]);

            // return response(auth()->user()->credit);
        }

        // $response = auth()->user()->charge(10000, $request->paymentMethod);
        // return $response;

        if (!Cashier::findBillable(auth()->user()->stripe_id)) {
            $customer = auth()->user()->createAsStripeCustomer();
        } else {
            $customer = auth()->user()->asStripeCustomer();
        }

        $amount = $request->amount * 100;

        // Pay directly
        try {

            // Create payment Intent
            $response = auth()->user()->charge($amount, $request->paymentMethod);
            // dd($response->id);
            // Save payment method if new.

            // return $response;

            // increase user credit
            auth()->user()->credit->amount += $amount;
            auth()->user()->credit->save();

            // Salveaza payment in baza de date?
            Payment::create([
                'user_id' => auth()->user()->id,
                'amount' => $amount,
                'payment_method_id' => $request->paymentMethod,
                'payment_intent_id' => $response->id,
            ]);

            // Save invoice

            Invoice::create([
                'user_id' => auth()->user()->id,
                'amount' => $amount,
                'uuid' => \App\Invoice::generateUUID(),
                'payment_intent_id' => $response->id,
                'status' => '1', // paid
            ]);

            // Save receipt?

            // return $request['payload'];

            // Add the payment method. TRY TO STORE PAYMENT for future use.
            // $stripe = new \Stripe\StripeClient(
            //     config('services.stripe.stripe_secret')
            //   );

            //   $the_pmnt = $stripe->paymentMethods->retrieve(
            //     $request['payload']['id']
            //   );

            //     return $stripe->paymentMethods->attach(
            //         $the_pmnt->id,
            //         ['customer' => auth()->user()->stripe_id]
            //     );

            //   $request['payload']['customer'] = $customer->id;

            //   $stripe->paymentMethods->create($request['payload']);

            // $stripe->paymentMethods->create([
            //     'type' => 'card',
            //     'card' => $request['payload']['card'],
            //     'customer' => $customer['id']
            //   ]);

            // return response()->json(['response' => $response]);
            return response()->json(['success' => 'A fost efectuata cu succes plata in valoare de ' . $request->amount . ' RON.', 'payment_method' => $request->paymentMethod, 'credit' => $request->amount . ' RON']);
        } catch (Laravel\Cashier\Exceptions\PaymentActionRequired $e) {
            return response()->json(['error' => $e->message]);
        } catch (Laravel\Cashier\Exceptions\IncompletePayment $e) {
            return response()->json(['error' => $e->message]);
        } catch (Laravel\Cashier\Exceptions\PaymentFailure $e) {
            return response()->json(['error' => $e->message]);
        } catch (Laravel\Cashier\Exceptions\CardError $e) {
            return response()->json(['error' => $e->message]);
        }

        // return response()->json(['success' => 'All is ok.']);
    }

    public function singleLaterCheckout(Request $request)
    {
        // Pay with the saved card

        //  \Stripe\Stripe::setApiKey('sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk');

        //  $stripe = new \Stripe\StripeClient(
        //     'sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk'
        //   );

        // get all payment mets
        //  $payment_mets = \Stripe\PaymentMethod::all([
        //      'customer' => auth()->user()->stripe_id,
        //      'type' => 'card',
        //  ]);

        // if(!$request->paymentMethodId){
        //     return response()->json([
        //         'error' => 'Nu am putut procesa plata. Ne pare rau. Incercati mai tarziu sau cu un alt card. Multumim!']);
        // }

        if (!auth()->user()->credit) {
            Credit::create([
                'user_id' => auth()->user()->id,
                'amount' => 0,
            ]);

            // return response(auth()->user()->credit);
        }

        // $response = auth()->user()->charge(10000, $request->paymentMethod);
        // return $response;

        if (!Cashier::findBillable(auth()->user()->stripe_id)) {
            $customer = auth()->user()->createAsStripeCustomer();
        }

        $customer = auth()->user()->asStripeCustomer();

        $amount = $request->amount * 100;

        try {

            // $stripe->paymentIntents->retrieve(
            //     'pi_1Dg9Go2eZvKYlo2CC5tGDdP6',
            //     []
            //   );

            //  $payment_intent = \Stripe\PaymentIntent::create([
            //     'amount' => 1010,
            //     'currency' => 'ron',
            //     'customer' => auth()->user()->stripe_id,
            //     'payment_method' => $request->paymentMethodId,
            //     'off_session' => true,
            //     'confirm' => true,
            //     ]);

            auth()->user()->credit->amount += $amount;
            auth()->user()->credit->save();

            return response()->json(['success' => 'All is ok with later payments.', 'response' => $payment_intent, 'credit' => $request->amount]);

        } catch (\Stripe\Exception\CardException $e) {
            // Error code will be authentication_required if authentication is needed
            // echo 'Error code is:' . $e->getError()->code;
            // $payment_intent_id = $e->getError()->payment_intent->id;
            // $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

            return response()->json([
                'error' => $e->getError()->code,
                'payment_intent_id' => $e->getError()->payment_intent->id,
                'intent' => \Stripe\PaymentIntent::retrieve($payment_intent_id),
            ]);

        }
    }

}
