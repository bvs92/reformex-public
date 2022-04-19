<?php

namespace App\Http\Controllers\API;

use App\CompanyReview;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyReviewsController extends Controller
{
    public function store(Request $request)
    {
        // return response()->json(['result' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'message' => 'required|min:60|max:186',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->valid();

        $review = new CompanyReview();
        $review->user_id = auth()->user()->id;
        $review->rating = $validated_fields['rating'];
        $review->message = $validated_fields['message'];

        DB::beginTransaction();

        if (!$review->save()) {
            DB::rollBack();
            return response()->json(['result' => false]);
        }

        DB::commit();
        return response()->json(['result' => true]);
    }

    public function save(Request $request, $review_id)
    {
        // return response()->json(['result' => $request->all()]);

        if (!auth()->user()->isAdmin()) {
            return response()->json(['result' => false]);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'message' => 'required|min:60|max:186',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->valid();

        $review = CompanyReview::where('id', $review_id)->first();

        if (!$review) {
            return response()->json(['result' => false]);
        }

        $review->rating = $validated_fields['rating'];
        $review->message = $validated_fields['message'];

        DB::beginTransaction();

        if (!$review->save()) {
            DB::rollBack();
            return response()->json(['result' => false]);
        }

        DB::commit();
        return response()->json(['result' => true]);
    }

    public function userHasReview()
    {
        $review = CompanyReview::where('user_id', auth()->user()->id)->first();

        if ($review) {
            return response()->json(['result' => true]);
        }

        return response()->json(['result' => false]);
    }

    public function getAll()
    {

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $reviews = CompanyReview::all();
        // $reviews = CompanyReview::where('status', 1)->get();

        if (!$reviews) {
            return response()->json(['reviews' => []]);
        }

        $review = $reviews->map(function ($item) {
            $item->user;
            $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'updated_at']);
            // get company name.
            $item['company_name'] = $item->user->company->name;
            $item->makeHidden(['company']);
            return $item;
        });

        return response()->json(['reviews' => $reviews]);
    }

    public function getAllPublic()
    {
        // $reviews = CompanyReview::all();
        $reviews = CompanyReview::where('status', 1)->get();

        if (!$reviews) {
            return response()->json(['reviews' => []]);
        }

        $review = $reviews->map(function ($item) {
            $item->user;
            $item->user->has_profile_photo = $item->user->hasProfilePhoto();
            $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
            $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at']);
            // get company name.
            $item['company_name'] = $item->user->company->name;
            $item->makeHidden(['company']);
            return $item;
        });

        return response()->json(['reviews' => $reviews]);
    }

    public function toggleStatus($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $review = CompanyReview::find($id);

        if (!$review) {
            return response()->json(['errors' => true]);
        }

        $review->status = !$review->status;

        if (!$review->save()) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $review = CompanyReview::find($id);

        if (!$review) {
            return response()->json(['errors' => true]);
        }

        if (!$review->delete()) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }
}
