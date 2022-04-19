<?php

namespace App\Http\Controllers\API;

use App\CompanyQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyQuestionsController extends Controller
{
    // get current company questions
    public function index()
    {
        $user = auth()->user();
        // if not login, error
        if (!$user) {
            return response()->json(['errors' => true]);
        }

        $company = \App\Company::where('user_id', $user->id)->first();

        if (!$company) {
            return response()->json(['errors' => true]);
        }

        $questions = $company->questions;

        if (!$questions || $questions->count() < 1) {
            return response()->json(['questions' => []]);
        }

        return response()->json(['questions' => $questions]);
    }

    public function store(Request $request)
    {
        // return response()->json(['result' => $request->all()]);

        if (!$user = auth()->user()) {
            return response()->json(['result' => false]);
        }

        if (!$user->isCompany()) {
            return response()->json(['result' => false]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'text' => 'required|min:60|max:300',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->valid();

        $question = new CompanyQuestion();
        $question->company_id = $user->company->id;
        $question->title = $validated_fields['title'];
        $question->text = $validated_fields['text'];

        DB::beginTransaction();

        if (!$question->save()) {
            DB::rollBack();
            return response()->json(['result' => false]);
        }

        DB::commit();
        return response()->json(['result' => true]);
    }

    public function edit(Request $request, $id)
    {
        // return response()->json(['result' => $request->all()]);

        if (!$user = auth()->user()) {
            return response()->json(['result' => false]);
        }

        if (!$user->isCompany()) {
            return response()->json(['result' => false]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'text' => 'required|min:60|max:300',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->valid();

        $question = CompanyQuestion::find($id);

        if (!$question) {
            return response()->json(['result' => false]);
        }

        $question->title = $validated_fields['title'];
        $question->text = $validated_fields['text'];

        DB::beginTransaction();

        if (!$question->save()) {
            DB::rollBack();
            return response()->json(['result' => false]);
        }

        DB::commit();
        return response()->json(['result' => true]);
    }

    public function delete($id)
    {
        // return response()->json(['result' => $request->all()]);

        if (!$user = auth()->user()) {
            return response()->json(['result' => false]);
        }

        if (!$user->isCompany()) {
            return response()->json(['result' => false]);
        }

        $question = CompanyQuestion::find($id);

        if (!$question) {
            return response()->json(['result' => false]);
        }

        if ($question->company_id != $user->company->id) {
            return response()->json(['result' => false]);
        }

        DB::beginTransaction();

        if (!$question->delete()) {
            DB::rollBack();
            return response()->json(['result' => false]);
        }

        DB::commit();
        return response()->json(['result' => true]);
    }

    public function publicQuestions($user_id)
    {

        $user = \App\User::find($user_id);
        if (!$user) {
            return response()->json(['questions' => []]);
        }

        if (!$user->isCompany()) {
            return response()->json(['questions' => []]);
        }

        $questions = CompanyQuestion::where('company_id', $user->company->id)->get();

        if (!$questions) {
            return response()->json(['questions' => []]);
        }

        return response()->json(['questions' => $questions]);
    }

}
