<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportReviewsController extends Controller
{
    public function report(Request $request, $report_id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => true]);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated = $validator->validated();

        $review = \App\Review::find($report_id);

        if ($review->professional_id != $user->professional->id) {
            return response()->json(['errors' => true]);
        }

        if ($review->report) {
            return response()->json(['errors' => true]);
        }

        // register review's report
        $report = new \App\ReportReview();
        $report->user_id = $user->id;
        $report->review_id = $review->id;
        $report->description = $validated['description'];

        if (!$report->save()) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);
    }
}
