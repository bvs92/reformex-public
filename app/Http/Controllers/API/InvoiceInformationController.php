<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\InvoiceInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InvoiceInformationController extends Controller
{
    public function information()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $information = $user->invoice_information ? $user->invoice_information : null;

        return response()->json(['success' => true, 'information' => $information]);
    }

    public function saveCompany(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        // return response()->json(['res' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'type' => ['required', Rule::in(['company', 'individual'])],
            'company_type' => [Rule::in(['PFA', 'II', 'IF', 'SRL', 'SRL-D', 'SNC', 'SA', 'SCS', 'SCA', 'SE'])],
            'company_name' => 'nullable',
            'phone' => 'nullable',
            'address' => 'nullable',
            'cui' => 'nullable',
            'number' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->validated();

        DB::beginTransaction();

        if ($user->invoice_information) {
            $invoice_information = $user->invoice_information;
        } else {
            $invoice_information = new InvoiceInformation();
        }

        $invoice_information->user_id = $user->id;
        $invoice_information->last_name = $validated_fields['last_name'];
        $invoice_information->first_name = $validated_fields['first_name'];
        $invoice_information->type = $validated_fields['type'];
        $invoice_information->company_type = $validated_fields['company_type'];
        $invoice_information->company_name = $validated_fields['company_name'];
        $invoice_information->phone = $validated_fields['phone'];
        $invoice_information->address = $validated_fields['address'];
        $invoice_information->cui = $validated_fields['cui'];
        $invoice_information->number = $validated_fields['number'];

        if (!$invoice_information->save()) {
            DB::rollback();
            return response()->json(['errors' => true]);
        }

        DB::commit();

        return response()->json(['success' => true]);
    }

    public function saveIndividual(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        // return response()->json(['res' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'type' => ['required', Rule::in(['company', 'individual'])],
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->validated();

        DB::beginTransaction();

        if ($user->invoice_information) {
            $invoice_information = $user->invoice_information;
        } else {
            $invoice_information = new InvoiceInformation();
        }

        $invoice_information->user_id = $user->id;
        $invoice_information->last_name = $validated_fields['last_name'];
        $invoice_information->first_name = $validated_fields['first_name'];
        $invoice_information->type = $validated_fields['type'];
        $invoice_information->phone = $validated_fields['phone'];
        $invoice_information->address = $validated_fields['address'];

        if (!$invoice_information->save()) {
            DB::rollback();
            return response()->json(['errors' => true]);
        }

        DB::commit();

        return response()->json(['success' => true]);
    }

}
