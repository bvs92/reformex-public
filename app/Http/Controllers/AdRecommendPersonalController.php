<?php

namespace App\Http\Controllers;

use App\AdRecommendCompany;
use App\Notifications\AdRecommendPaymentNotification;
use App\Payment;
use App\Period;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Stripe\Stripe;

class AdRecommendPersonalController extends Controller
{
    public function index()
    {
        return view('volgh.ads_recommend.index');
    }

    public function activate($uuid)
    {
        return view('volgh.ads_recommend.activate')->with(['uuid' => $uuid]);
    }

    public function success(Request $request, $ad_uuid, $period_id)
    {
        // verifica daca exista id sesiune plata
        if (!$request->get('session_id')) {
            return Redirect::route('advertising.ad_recommend.personal.show', ['uuid' => $ad_uuid])->with('message_payment_expired', 'Ceva nu a funcționat corect.');
        }

        $STRIPE_API_SECRET = env('STRIPE_SECRET');
        Stripe::setApiKey($STRIPE_API_SECRET);

        try {
            // preia din Stripe sesiunea de plata
            $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));
        } catch (Exception $e) {
            return Redirect::route('advertising.ad_recommend.personal.show', ['uuid' => $ad_uuid])->with('message_payment_expired', 'Ceva nu a funcționat corect.');
        }

        // verifica daca exista sesiune pentru id respectiv.
        if (!$session) {
            return Redirect::route('advertising.ad_recommend.personal.show', ['uuid' => $ad_uuid])->with('message_payment_expired', 'Ceva nu a funcționat corect.');
        }

        if ($session->status == 'expired') {
            return Redirect::route('advertising.ad_recommend.personal.show', ['uuid' => $ad_uuid])->with('message_payment_expired', 'Sesiunea a expirat. Plata nu a fost efectuată.');
        }

        // verifica daca plata a fost procesata cu succes.
        if ($session->payment_status != 'paid') {
            return Redirect::route('advertising.ad_recommend.personal.show', ['uuid' => $ad_uuid])->with('message_payment_expired', 'Ceva nu a funcționat. Plata nu a fost procesată.');
        }

        // verifica daca sesiunea curenta a mai fost procesata in trecut pentru utilizatorul curent
        $payment = Payment::where('checkout_id', $session->id)->where('user_id', auth()->user()->id)->first();
        if ($payment) {
            return Redirect::route('advertising.ad_recommend.personal.show', ['uuid' => $ad_uuid])->with('message_payment_expired', 'Ceva nu a funcționat. Plata a mai fost procesată o dată.');
        }
        // dd($session);

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return redirect()->back();
        }

        $ad = AdRecommendCompany::where('uuid', $ad_uuid)->first();

        if (!$ad) {
            return redirect()->back();
        }

        $period = Period::find($period_id);

        if (!$period) {
            return redirect()->back();
        }

        // save period for banner.

        $ad->periods()->attach($period->id);

        $ad->editable = 0;
        $ad->processing = 1;
        $ad->paid = 1; // mark banner as paid
        $ad->save();

        // create local payment info
        $payment = new Payment();
        $payment->uuid = Str::upper($this->generateUUID());
        $payment->name = "Pachet anunț firmă recomandată: " . $period->days . " zile.";
        $payment->user_id = auth()->user()->id;
        $payment->checkout_id = $session->id;
        $payment->payment_status = $session->payment_status;
        $payment->payment_intent = $session->payment_intent;
        $payment->amount_total = $session->amount_total;

        $ad->payments()->save($payment);

        // notify admin
        // notify user that the banner is active
        $admins = User::role('admin')->get();
        Notification::send($admins, new AdRecommendPaymentNotification($ad, $payment));

        // return redirect()->route('advertising.banners.personal.success.payment', $ad_uuid);
        return Redirect::route('advertising.ad_recommend.personal.show', ['uuid' => $ad->uuid])->with('message_payment', 'Plată efectuată cu succes. Verificăm informațiile și vom activa anunțul.');
    }

    public function success_payment_banner($ad_uuid)
    {
        return view('volgh.banners.show')->with(['uuid' => $uuid]);
    }

    public function show($uuid)
    {
        $banner = AdRecommendCompany::where('uuid', $uuid)->where('user_id', auth()->user()->id)->first();
        if (!$banner) {
            return redirect()->route('advertising.ad_recommend.personal.index');
        }

        return view('volgh.ads_recommend.show')->with(['uuid' => $uuid]);
    }

    public function create()
    {
        return view('volgh.ads_recommend.create');
    }

    private function generateUUID()
    {
        // genereaza id
        $res = Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while (Payment::where('uuid', $id)->get()->count() > 0) {
            // regenereaza daca exista
            $res = Str::uuid();
            // $res = rand(0, 99);
            $id = substr($res, 0, 8);
            // echo $id . '<br/>';
        }

        // echo 'JOS: ' . $id . '<br/>';
        return $id;

        // => id este unic si poate fi atasat unei cereri.
    }
}
