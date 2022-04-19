<?php

namespace App\Http\Controllers\API;

use App\Banner;
use App\Http\Controllers\Controller;
use App\Period;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function checkout($banner_uuid, $period_id)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $banner = Banner::where('uuid', $banner_uuid)->first();

        if (!$banner) {
            return response()->json(['errors' => true]);
        }

        if ($banner->user_id !== $user->id) {
            return response()->json(['errors' => true]);
        }

        $period = Period::find($period_id);

        if (!$period) {
            return response()->json(['errors' => true]);
        }

        $total_cost = $this->calculateBannerCost($banner->categories, $period);

        // dd('atins');
        // require 'vendor/autoload.php';
        Stripe::setApiKey('sk_test_51Gswe0I0uyZGzh5LR6Doq1vzNizCOKt80ntBgPHfMdBuhoFFaFDHZGn1CHB97TXYhtxHO23lzXPcgbljpxoouZ7b00s4FMOiWk');
        header('Content-Type: application/json');
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $product = \Stripe\Product::create([
            'name' => 'Anunț Banner',
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
            'success_url' => $YOUR_DOMAIN . '/publicitate/banner/detalii/' . $banner_uuid . '/succes',
            'cancel_url' => $YOUR_DOMAIN . '/publicitate/banner/detalii/' . $banner_uuid,
        ]);

        return redirect()->away($checkout_session->url);

        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);
        // Redirect::away($stripe_url);
        // return $checkout_session;
        // redirect($stripe_url);
    }

    private function calculateBannerCost($banner_categories, $period)
    {
        $price_per_day = 5;

        if (!$banner_categories) {
            return response()->json(['errors' => true]);
        }

        if ($banner_categories->count() > 0) {
            $total_categories = $banner_categories->count();
        } else {
            return response()->json(['errors' => true]);
        }

        $cost_categories = $total_categories * $price_per_day;
        return $total_cost = $cost_categories * $period->days;
    }
}
