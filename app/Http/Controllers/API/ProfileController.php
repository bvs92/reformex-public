<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

    public function saveCompanyProfile(Request $request)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            // 'year_made' => 'required|string',
            'phone' => 'required|string',
            // 'workers' => 'required|numeric|min:0',
            'cui' => 'required',
            'register_number' => 'required',
            // 'administrative' => 'required',
            // 'city' => 'required',
            // 'address' => 'required',
            // 'bio' => 'nullable',
            // 'website' => 'nullable',
            'company_type' => ['required', Rule::in(['PFA', 'II', 'IF', 'SRL', 'SRL-D', 'SNC', 'SA', 'SCS', 'SCA', 'SE'])],
            'company_location' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $validated = $validator->validated();

        // return response()->json(['res' => gettype($validated['company_location'])]);

        $validated['user_id'] = auth()->user()->id;

        if (auth()->user()->isCompany()) {
            $company = auth()->user()->company;
        } else {
            $company = new \App\Company;
        }

        $company->name = $validated['name'];
        // $company->year_made = $validated['year_made'];
        $company->phone = $validated['phone'];
        // $company->workers = $validated['workers'];
        $company->cui = $validated['cui'];
        $company->register_number = $validated['register_number'];
        // $company->administrative = $validated['administrative'];
        // $company->city = $validated['city'];
        // $company->address = $validated['address'];
        $company->user_id = auth()->user()->id;
        // $company->bio = $validated['bio'];
        // $company->website = $validated['website'];
        $company->company_type = $validated['company_type'];

        if (!$company->save($validated)) {
            return response()->json(['error' => 'Am intampinat erori. Va rugam incercati mai tarziu.']);
        }

        // return response()->json(['res' => $validated['company_location']]);

        // location
        if ($company->location) {
            $company->location->value = $validated['company_location']['value'];
            $company->location->lat = $validated['company_location']['lat'];
            $company->location->lng = $validated['company_location']['lng'];
            $company->location->details = json_encode($validated['company_location']['complete']);
            $company->location->save();
        } else {
            $location = new \App\CompanyLocation;
            $location->company_id = $company->id;
            $location->value = $validated['company_location']['value'];
            $location->lat = $validated['company_location']['lat'];
            $location->lng = $validated['company_location']['lng'];
            $location->details = json_encode($validated['company_location']['complete']);
            $location->save();
        }

        return response()->json(['success' => 'Modificarile au fost efectuate cu succes.', 'company' => $company]);
    }

    public function saveInactiveCompanyProfile(Request $request)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'year_made' => 'required|string',
            'phone' => 'required|string',
            'workers' => 'required|numeric|min:0',
            'cui' => 'required|numeric',
            'register_number' => 'required',
            'administrative' => 'required',
            'city' => 'required',
            'address' => 'required',
            'bio' => 'nullable',
            'website' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $validated = $validator->validated();

        $validated['user_id'] = auth()->user()->id;

        if (auth()->user()->isCompany()) {
            $company = auth()->user()->company;
        } else {
            $company = new \App\Company;
        }

        $company->name = $validated['name'];
        $company->year_made = $validated['year_made'];
        $company->phone = $validated['phone'];
        $company->workers = $validated['workers'];
        $company->cui = $validated['cui'];
        $company->register_number = $validated['register_number'];
        $company->administrative = $validated['administrative'];
        $company->city = $validated['city'];
        $company->address = $validated['address'];
        $company->user_id = auth()->user()->id;
        $company->bio = $validated['bio'];
        $company->website = $validated['website'];

        if (!$company->save($validated)) {
            return response()->json(['error' => 'Am intampinat erori. Va rugam incercati mai tarziu.']);
        }

        if (!auth()->user()->registration) {
            $registration = new \App\Registration;
        } else {
            $registration = auth()->user()->registration;
        }

        $registration->status = 1;
        $registration->save();

        // notify admin?

        return response()->json(['success' => 'Modificarile au fost efectuate cu succes.', 'company' => $company, 'registration' => auth()->user()->registration]);
    }

    public function saveCategories(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (count($request->categories) <= 0) {
            return response()->json(['error' => 'Selectati cel putin o categorie.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        // dd($validated['categories']);
        if (!auth()->user()->professional->categories()->sync($request->categories)) {
            return response()->json(['error' => 'Am intampinat erori. Va rugam incercati mai tarziu.']);
        }

        return response()->json(['success' => 'Modificarile au fost efectuate cu succes.']);

        // dd($request->all());
    }

    public function eliminateCategories()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!auth()->user()->professional->categories()->detach()) {
            return response()->json(['error' => 'Am intampinat erori. Va rugam incercati mai tarziu.']);
        }

        return response()->json(['success' => 'Modificarile au fost efectuate cu succes.']);

    }

    public function saveLocation(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $validator = Validator::make($request->all(), [
            'city' => 'required',
            'range' => 'required|numeric|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $pro = auth()->user()->professional;

        $pro->range = $request->range * 1000; // transformare in M
        $pro->city = $request->input('city');
        $pro->administrative = $request->input('administrative');
        $pro->postal_code = $request->input('postal_code');
        $pro->lat = $request->input('lat');
        $pro->lng = $request->input('lng');

        // dd($validated['categories']);
        if (!$pro->save()) {
            return response()->json(['error' => 'Am intampinat erori. Va rugam incercati mai tarziu.']);
        }

        return response()->json(['success' => 'Modificarile au fost efectuate cu succes.']);
    }

}
