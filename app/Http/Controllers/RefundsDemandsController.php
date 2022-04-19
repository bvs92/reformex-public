<?php

namespace App\Http\Controllers;

use App\User;
use App\Ticket;
use App\RefundsDemand;
use Illuminate\Http\Request;

class RefundsDemandsController extends Controller
{


    public function __construct()
    {
        $this->middleware('role:admin')->only(['markApprove', 'markDeny']);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $refunds_demands = RefundsDemand::paginate(20);

        return view('volgh.refunds.index', [
            'refunds_demands' => $refunds_demands
        ]);
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
        // dd($request->all());

        $validated = $request->validate([
            'payment_intent_id' => 'required'
        ]);

        $validated['user_id'] = auth()->user()->id;

        $stripe = new \Stripe\StripeClient(
            config('services.stripe.stripe_secret')
          );
        
        $paymentIntent = $stripe->paymentIntents->retrieve(
            $validated['payment_intent_id'],
            []
        );

        if(!$paymentIntent){
            return redirect()->back();
        }


        

        $ticket = new Ticket();
        
        $ticket->priority = '3';
        $ticket->status = 0;
        $ticket->user_id = auth()->user()->id;
        $ticket->message = "Stimate/stimata,\n\r Prin actiunea curenta doresc restituirea platii cu identificator #" . $paymentIntent->id . " in valoare de ". ($paymentIntent->amount / 100) ." RON.\n\r";
        if(isset($validated['message'])){
            $ticket->message .= "\n\r";
            $ticket->message .= $validated['message'];
        }
        $ticket->message .= "\n\r";
        $ticket->message .= "Cu stima, " . auth()->user()->getName();
        $ticket->subject = "Rambursare plata #" . $paymentIntent->id;
        $ticket->save();
        

        $validated['ticket_id'] = $ticket->id;
        if(!$refundDemand = RefundsDemand::create($validated)){
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        

        return redirect()->route('payments.show', $validated['payment_intent_id'])->with('success', 'Cererea de rambursare a fost inregistrata.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $stripe = new \Stripe\StripeClient(
            config('services.stripe.stripe_secret')
          );

        $refund_demand = RefundsDemand::findOrFail($id);

        $paymentIntent = $stripe->paymentIntents->retrieve(
            $refund_demand->payment_intent_id,
            []
        );

        $this->authorize('update', $refund_demand);

        // dd($paymentIntent);

        if($paymentIntent->payment_method){
            $payment_method = $stripe->paymentMethods->retrieve(
              $paymentIntent->payment_method,
              []
            );
        } else {
          $payment_method = null;
        }

        $customer = User::where('stripe_id', $paymentIntent->customer)->first();

        $refunds_demands = RefundsDemand::where('payment_intent_id', $paymentIntent->id)->get();  

        return view('volgh.refunds.view', [
            'demand' => $refund_demand,
            'payment_method' => $payment_method,
            'customer'    => $customer,
            'payment' => $paymentIntent,
            'refunds_demands' => $refunds_demands
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


    public function markApprove(Request $request, $id)
    {
        $demand = RefundsDemand::findOrFail($id);

        $demand->status = '1';

        if(!$demand->save()){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam sa incercati mai tarziu!');
        }
        
        return redirect()->route('refundsdemands.show', $demand->id)->with('success', 'Actiune executata cu succes.');
    }

    public function markDeny(Request $request, $id)
    {
        $demand = RefundsDemand::findOrFail($id);

        $demand->status = '2';

        if(!$demand->save()){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam sa incercati mai tarziu!');
        }
        
        return redirect()->route('refundsdemands.show', $demand->id)->with('success', 'Actiune executata cu succes.');
    }
}
