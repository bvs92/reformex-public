<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\TimelineAction;
use App\Review;
use App\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ReviewsController extends Controller
{
    public function store(Request $request, $timeline_id)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json($user);

        $validator = \Validator::make($request->all(), [
            // 'professional_id' => 'required|exists:professionals,id',
            // 'timeline_id' => 'required|exists:timelines,id',
            'rating' => 'required|numeric|min:1|max:5',
            'message' => 'required|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validated = $validator->validate();

        $timeline = Timeline::find($timeline_id);

        if (!$timeline) {
            return response()->json(['errors' => 'Nu am gasit nimic.']);
        }

        // check if current user is authorized to view the timeline.
        if (!$user->can('update_client', $timeline)) {
            return response()->json(['errors' => 'Error getting the data specified.']);
        }

        $demand = $timeline->demand;

        if ($demand->review) {
            $review = $demand->review()->where('professional_id', $timeline->professional_id)->first();

            if ($review) {
                if ($review->professional_id == $timeline->professional_id) {
                    return response()->json(['errors' => 'Exista deja o recenzie acordata acestui profesionist.']);
                }
            }

        }
        // create new review for current demand.

        $review = new Review;
        $review->timeline_id = $timeline->id;
        $review->professional_id = $timeline->professional_id;
        $review->demand_id = $timeline->demand_id;
        $review->user_id = $user->id;
        $review->name = strtoupper($user->last_name[0]) . ". " . $user->first_name;
        $review->message = $validated['message'];
        $review->rating = $validated['rating'];

        if (!$review->save()) {
            return response()->json('errors', 'Am intampinat erori. Incercati mai tarziu!');
        }

        // notification
        Notification::send($timeline->professional->user, new TimelineAction($timeline, $timeline->user, 'review_created'));
        // event
        event(new \App\Events\ClientReviewEvent($timeline, $user));

        return response()->json(['review' => $review]);

    }

    public function personal_reviews($page = 1)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => true]);
        }

        $per_page = 30;

        if ($page && $page > 1) {
            $reviews_result = $user->professional->reviews()->skip($page - 1 * $per_page)->paginate($per_page);
        } else {
            $reviews_result = $user->professional->reviews()->paginate($per_page);
        }

        // paginate

        if ($reviews_result->all()) {
            $reviews_result->data = $reviews_result->map(function ($item) {
                if ($item->isReported()) {
                    $item['is_reported'] = true;
                } else {
                    $item['is_reported'] = false;
                }

                return $item;
            });
        }

        return response()->json(['reviews' => $reviews_result]);
    }

    // update review by admin
    public function update(Request $request, $id)
    {
        # code...
    }

    // delete review by admin
    public function delete($id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $review = \App\Review::find($id);

        if (!$review->delete()) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }

    // list all review for admin
    public function all_reviews($page = 1)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $per_page = 25;

        if ($page && $page > 1) {
            $reviews_result = \App\Review::skip($page - 1 * $per_page)->orderBy('created_at', 'desc')->paginate($per_page);
        } else {
            $reviews_result = \App\Review::orderBy('created_at', 'desc')->paginate($per_page);
        }

        // paginate

        if ($reviews_result->all()) {
            $reviews_result->data = $reviews_result->map(function ($item) {
                $item->report;

                $item['user_name'] = $item->professional ? $item->professional->user->getTheName() : 'Indisponibil';
                $item->makeHidden('professional');

                if ($item->isReported()) {
                    $item['is_reported'] = true;
                    $item->report['full_name'] = $item->report->user->last_name . ' ' . $item->report->user->first_name;
                    $item->report->makeHidden('user');
                } else {
                    $item['is_reported'] = false;
                }

                return $item;
            });
        }

        return response()->json(['reviews' => $reviews_result]);
    }

    public function all_user_reviews($user_id, $page = 1)
    {
        try {
            $user = \App\User::find($user_id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        // return response()->json(['reviews' => $user->professional->reviews]);

        if (!$user->isPro()) {
            return response()->json(['errors' => true]);
        }

        $per_page = 25;

        if ($page && $page > 1) {
            $reviews_result = $user->professional->reviews()->skip($page - 1 * $per_page)->orderBy('created_at', 'desc')->paginate($per_page);
        } else {
            $reviews_result = $user->professional->reviews()->orderBy('created_at', 'desc')->paginate($per_page);
        }

        // paginate

        if ($reviews_result->all()) {
            $reviews_result->data = $reviews_result->map(function ($item) {
                $item->report;

                $item['user_name'] = $item->professional ? $item->professional->user->getTheName() : 'Indisponibil';
                $item->makeHidden('professional');

                if ($item->isReported()) {
                    $item['is_reported'] = true;
                    $item->report['full_name'] = $item->report->user->last_name . ' ' . $item->report->user->first_name;
                    $item->report->makeHidden('user');
                } else {
                    $item['is_reported'] = false;
                }

                return $item;
            });
        }

        return response()->json(['reviews' => $reviews_result]);
    }

    // list all review for admin
    public function list_reported_reviews($page = 1)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $per_page = 25;

        if ($page && $page > 1) {
            $reviews_result = \App\Review::has('report')->orderBy('created_at', 'desc')->skip($page - 1 * $per_page)->paginate($per_page);

        } else {
            $reviews_result = \App\Review::has('report')->orderBy('created_at', 'desc')->paginate($per_page);
        }

        // paginate

        if ($reviews_result->all()) {
            $reviews_result->data = $reviews_result->map(function ($item) {
                $item->report;
                // $item->user;
                $item['user_name'] = $item->professional->user->getTheName();
                $item->makeHidden('professional');
                $item->makeHidden('user');

                if ($item->isReported()) {
                    $item['is_reported'] = true;
                    $item->report['full_name'] = $item->report->user->last_name . ' ' . $item->report->user->first_name;
                    $item->report->makeHidden('user');
                } else {
                    $item['is_reported'] = false;
                }

                return $item;
            });
        }

        return response()->json(['reviews' => $reviews_result]);
    }

    // show single

}
