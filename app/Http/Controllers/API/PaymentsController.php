<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Payment;

class PaymentsController extends Controller
{

    public function personal()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $payments = $user->payments()->orderBy('created_at', 'desc')->get();

        if ($payments->count() > 0) {
            $payments = $payments->map(function ($item) {
                $item->invoice;
                return $item;
            });
        }

        return response()->json(['payments' => $payments]);
    }

    public function all()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $payments = Payment::orderBy('created_at', 'desc')->get();

        if ($payments->count() > 0) {
            $payments = $payments->map(function ($item) {
                $item->user;
                $item->invoice;
                return $item;
            });
        }

        return response()->json(['payments' => $payments, 'total' => $payments->count()]);
    }

    public function allInvoices()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $payments = Payment::with('invoice')->orderBy('created_at', 'desc')->get();

        if ($payments->count() < 1) {
            return response()->json(['errors' => true]);
        }

        $payments = $payments->map(function ($item) {
            $item->user;
            $item->invoice;
            return $item;
        });

        $payments = $payments->filter(function ($item) {
            if ($item->invoice !== null) {
                return $item;
            }
        });

        // $payments->toArray();

        return response()->json(['payments' => $payments, 'total' => $payments->count()]);
    }

    // return single payment
    public function single($uuid)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $payment = Payment::where('uuid', $uuid)->first();

        if (!$payment) {
            return response()->json(['errors' => true]);
        }

        $payment->paymentable;
        $payment->user;
        $payment->user->invoice_information;
        $payment->invoice;

        return response()->json(['payment' => $payment]);
    }

    // public function existing_cards()
    // {
    //     \Stripe\Stripe::setApiKey('sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk');

    //     $intent = \Stripe\SetupIntent::create([
    //         'customer' => auth()->user()->stripe_id
    //     ]);

    //     # end for saving the card

    //     // dd($intent->client_secret);

    //     $existing_methods = \Stripe\PaymentMethod::all([
    //         // 'customer' => 'cus_HiyRIwhnHPuXwq',
    //         'customer' => $intent->customer,
    //         'type' => 'card',
    //       ]);

    //     //   dd($intent);
    //     //   dd($existing_methods->data);

    //     return response()->json([
    //         'existing_cards' => $existing_methods->data
    //     ]);
    // }
}
