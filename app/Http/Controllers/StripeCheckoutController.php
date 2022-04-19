<?php

namespace App\Http\Controllers;

use App\AdRecommendCompany;
use App\Banner;
use App\Period;
use Stripe\Stripe;

class StripeCheckoutController extends Controller
{

    public function index()
    {
        return view('stripe.index');
    }

    public function cancel()
    {
        return view('stripe.cancel');
    }

    public function success()
    {
        return view('volgh.banners.success');
    }

    public function checkout($banner_uuid, $period_id)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return redirect()->back();
        }

        $banner = Banner::where('uuid', $banner_uuid)->first();

        if (!$banner) {
            return redirect()->back();
        }

        if ($banner->user_id !== $user->id) {
            return redirect()->back();
        }

        $period = Period::find($period_id);

        if (!$period) {
            return redirect()->back();
        }

        $total_cost = $this->calculateBannerCost($banner->categories, $period);

        // save payment, payment_intent, payment_status

        // dd('atins');
        // require 'vendor/autoload.php';
        $STRIPE_API_SECRET = env('STRIPE_SECRET');
        Stripe::setApiKey($STRIPE_API_SECRET);
        header('Content-Type: application/json');
        $YOUR_DOMAIN = url('/');

        $product = \Stripe\Product::create([
            'name' => 'Pachet Anunț Banner',
            'description' => 'Perioadă valabilitate anunț: ' . $period->days . ' zile.',
            // 'images' => ['https://example.com/t-shirt.png'],
        ]);

        $price = \Stripe\Price::create([
            'product' => $product->id,
            'unit_amount' => $total_cost * 100, // pret in unitati
            'currency' => 'ron',
        ]);

        // dd($price);

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                # TODO: replace this with the `price` of the product you want to sell
                'price' => $price,
                'quantity' => 1,
            ]],
            'payment_method_types' => [
                'card',
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/publicitate/banner/detalii/' . $banner_uuid . '/succes/' . $period->id . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/publicitate/banner/detalii/' . $banner_uuid,
        ]);

        // $checkout_session->success_url = $YOUR_DOMAIN . '/publicitate/banner/detalii/' . $banner_uuid . '/succes/' . $period->id . '/' . $checkout_session->id;

        // return $checkout_session->id;

        // $checkout_session->success_url = $checkout_session->success_url . '/' . $checkout_session->id;

        // return $checkout_session->success_url;
        return redirect()->away($checkout_session->url);

        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);
        // Redirect::away($stripe_url);
        // return $checkout_session;
        // redirect($stripe_url);
    }

    public function checkout_ad_recommend($ad_uuid, $period_id)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return redirect()->back();
        }

        $ad = AdRecommendCompany::where('uuid', $ad_uuid)->first();

        if (!$ad) {
            return redirect()->back();
        }

        if ($ad->user_id !== $user->id) {
            return redirect()->back();
        }

        $period = Period::find($period_id);

        if (!$period) {
            return redirect()->back();
        }

        $total_cost = $this->calculateBannerCost($ad->categories, $period);

        // save payment, payment_intent, payment_status

        // dd('atins');
        // require 'vendor/autoload.php';
        $STRIPE_API_SECRET = env('STRIPE_SECRET');
        Stripe::setApiKey($STRIPE_API_SECRET);
        header('Content-Type: application/json');
        $YOUR_DOMAIN = url('/');

        $product = \Stripe\Product::create([
            'name' => 'Pachet Anunț Firmă Recomandată',
            'description' => 'Perioadă valabilitate anunț: ' . $period->days . ' zile.',
            // 'images' => ['https://example.com/t-shirt.png'],
        ]);

        $price = \Stripe\Price::create([
            'product' => $product->id,
            'unit_amount' => $total_cost * 100, // pret in unitati
            'currency' => 'ron',
        ]);

        // dd($price);

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                # TODO: replace this with the `price` of the product you want to sell
                'price' => $price,
                'quantity' => 1,
            ]],
            'payment_method_types' => [
                'card',
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/publicitate/anunturi-recomandate/detalii/' . $ad_uuid . '/succes/' . $period->id . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/publicitate/anunturi-recomandate/detalii/' . $ad_uuid,
        ]);

        // $checkout_session->success_url = $YOUR_DOMAIN . '/publicitate/banner/detalii/' . $banner_uuid . '/succes/' . $period->id . '/' . $checkout_session->id;

        // return $checkout_session->id;

        // $checkout_session->success_url = $checkout_session->success_url . '/' . $checkout_session->id;

        // return $checkout_session->success_url;
        return redirect()->away($checkout_session->url);

        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);
        // Redirect::away($stripe_url);
        // return $checkout_session;
        // redirect($stripe_url);
    }

    private function calculateBannerCost($banner_categories, $period)
    {
        $total_categories = $banner_categories->count();

        if ($total_categories == 0) {
            return redirect()->back();
        }

        if ($total_categories >= 2) {
            $total_cost = $total_categories * intval($period->price);
            $total_cost = intval($total_cost / 1.1); // 10% discount
        } else if ($total_categories >= 4) {
            $total_cost = $total_categories * intval($period->price);
            $total_cost = intval($total_cost / 1.2); // 20% discount
        } else {
            $total_cost = $total_categories * intval($period->price);
        }

        return $total_cost;

    }
}
