<?php

namespace App\Http\Controllers;

use App\Category;
use App\Company;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $categories = Category::all();
        $my_categories = auth()->user()->getAssocCategories();
        // $user = User::find($id);
        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.editprofile', [
            'categories' => $categories,
            'my_categories' => $my_categories,
            'user' => auth()->user(),
        ]);
    }

    public function edit_password()
    {
        $categories = Category::all();
        $my_categories = auth()->user()->getAssocCategories();
        // $user = User::find($id);
        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.editprofile-password', [
            'categories' => $categories,
            'my_categories' => $my_categories,
            'user' => auth()->user(),
        ]);
    }

    public function edit_socials()
    {
        $categories = Category::all();
        $my_categories = auth()->user()->getAssocCategories();
        // $user = User::find($id);
        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.editprofile-socials', [
            'categories' => $categories,
            'my_categories' => $my_categories,
            'user' => auth()->user(),
        ]);
    }

    public function edit_personal()
    {
        $categories = Category::all();
        $my_categories = auth()->user()->getAssocCategories();
        // $user = User::find($id);
        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.editprofile-personal', [
            'categories' => $categories,
            'my_categories' => $my_categories,
            'user' => auth()->user(),
        ]);
    }

    public function edit_personal_vue()
    {
        return view('volgh.profile.settings-personal');
    }

    public function editUserProfile($id)
    {
        // protected by middleware role:admin

        $categories = Category::all();
        $user = User::findOrFail($id);
        $my_categories = $user->getAssocCategories();
        if ($user->company) {
            $company = $user->company;
        } else {
            $company = new Company;
        }

        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.edit', [
            'categories' => $categories,
            'my_categories' => $my_categories,
            'user' => $user,
            'company' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function view()
    {
        $categories = Category::all();
        $my_categories = auth()->user()->getAssocCategories();
        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.show', [
            'categories' => $categories,
            'my_categories' => $my_categories,
        ]);

    }

    public function viewProProfile($id)
    {

        // tip profil public? apare la demands/mine

        $user = User::findOrFail($id);

        // if(!$user->isCompany()){
        //     return redirect()->route('users.index');
        // } -- anyone should see this profile.

        $categories = Category::all();
        $my_categories = auth()->user()->getAssocCategories();
        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        $reviews = $user->reviews;

        return view('volgh.profile.pro', [
            'categories' => $categories,
            'my_categories' => $my_categories,
            'user' => $user,
            'reviews' => $reviews,
        ]);

    }

    // folosit doar de catre admin
    public function viewUserProfile($id)
    {
        $user = User::findOrFail($id);
        $categories = Category::all();
        // $user_categories = $user->getAssocCategories();
        $roles = Role::all();
        $reviews = $user->reviews;

        return view('volgh.profile.default', [
            'categories' => $categories,
            // 'user_categories' => $user_categories,
            'user' => $user,
            'roles' => $roles,
            'reviews' => $reviews,
        ]);
    }

    public function changePassword()
    {
        $validated = request()->validate([
            'password' => 'required|min:6|max:255',
            'new_password' => 'required|min:6|max:255|string|confirmed',
        ]);

        if (Hash::check($validated['password'], auth()->user()->password)) {
            auth()->user()->password = Hash::make($validated['password']);
            if (!auth()->user()->save()) {
                return redirect()->back()->with('error', 'Eroare. Va rugam sa incercati mai tarziu.');
            }

            return redirect()->route('user.profile')->with('success', 'Parola a fost modificata!');
        }

        return redirect()->back()->with('error', 'Eroare. Parola curenta tastata este gresita.');
    }

    public function changeInformation()
    {
        $validated = request()->validate([
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
        ]);

        if (!auth()->user()->update($validated)) {
            return redirect()->back()->with('error', 'Eroare. Va rugam sa incercati mai tarziu.');
        }

        return redirect()->route('user.profile')->with('success', 'Modificare efectuata cu succes.');
    }

    public function changeProfilePhoto(Request $request)
    {
        $validated = $request->validate([
            'profile_photo' => 'required|image|max:100000000',
        ]);

        if (!$request->hasFile('profile_photo')) {
            return redirect()->back();
        }

        $file = $validated['profile_photo'];

        // Get image original extension
        $ext = $file->getClientOriginalExtension();

        // Compose the name
        $file_name = 'profile-' . time() . "-" . auth()->user()->id . '.' . $ext;

        $data['user_id'] = auth()->user()->id;
        $data['profile_photo'] = $file_name;

        // Check if user has profile & do the corresponding actions. Create one Or edit an existing.
        if (!auth()->user()->profile) {

            // Upload file

            if (!File::isDirectory(public_path('images/avatars'))) {
                File::makeDirectory(public_path('images/avatars'), 0777, true, true);
            }

            // Normal image
            Image::make($file)
                ->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('images/avatars/' . $file_name));

            if (!File::isDirectory(public_path('images/avatars/thumbnails'))) {
                File::makeDirectory(public_path('images/avatars/thumbnails'), 0777, true, true);
            }

            // Thumbnail image
            Image::make($file)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('images/avatars/thumbnails/' . $file_name));

            if (!Profile::create($data)) {
                return redirect()->back()->with('error', 'Eroare. Va rugam sa incercati mai tarziu.');
            }
            return redirect()->route('user.profile')->with('success', 'Modificare efectuata cu succes.');

        } else {
            $current_profile = auth()->user()->profile;

            // 1. Delete existing photo (if any)

            if ($current_profile->profile_photo && $current_profile->profile_photo != 'default-photo.png') {

                if (public_path('images/avatars/' . $current_profile->profile_photo) !== null) {
                    unlink(public_path('images/avatars/' . $current_profile->profile_photo));
                }

                if (public_path('images/avatars/thumbnails/' . $current_profile->profile_photo) !== null) {
                    unlink(public_path('images/avatars/thumbnails/' . $current_profile->profile_photo));
                }

            }

            // 2. Upload file

            // Image::make($file)
            //     ->resize(300, 300, function ($constraint) {
            //         $constraint->aspectRatio();
            //     })
            //     ->save(public_path('images/avatars/' . $file_name));

            if (!File::isDirectory(public_path('images/avatars'))) {
                File::makeDirectory(public_path('images/avatars'), 0777, true, true);
            }

            if (!File::isDirectory(public_path('images/avatars/thumbnails'))) {
                File::makeDirectory(public_path('images/avatars/thumbnails'), 0777, true, true);
            }

            // Normal image
            Image::make($file)
                ->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('images/avatars/' . $file_name));

            // Thumbnail image
            Image::make($file)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('images/avatars/thumbnails/' . $file_name));

            if (!auth()->user()->profile()->update($data)) {
                return redirect()->back()->with('error', 'Eroare. Va rugam sa incercati mai tarziu.');
            }
            return redirect()->route('user.profile')->with('success', 'Modificare efectuata cu succes.');
        } // end if

    }

    public function saveCompanyProfile(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|min:2|max:255',
            'year_made' => 'nullable|numeric|min:1900',
            'phone' => 'nullable|numeric',
            'workers' => 'nullable|numeric|min:0',
            'cui' => 'nullable|numeric',
            'register_number' => 'nullable',
            'administrative_company' => 'nullable',
            'city_company' => 'nullable',
            'address_company' => 'nullable',
            'bio' => 'nullable',
            'website' => 'nullable',
        ]);

        $validated['user_id'] = auth()->user()->id;

        if (auth()->user()->isCompany()) {
            $company = auth()->user()->company;
        } else {
            $company = new Company;
        }

        $company->name = $validated['company_name'];
        $company->year_made = $validated['year_made'];
        $company->phone = $validated['phone'];
        $company->workers = $validated['workers'];
        $company->cui = $validated['cui'];
        $company->register_number = $validated['register_number'];
        $company->administrative = $validated['administrative_company'];
        $company->city = $validated['city_company'];
        $company->address = $validated['address_company'];
        $company->user_id = auth()->user()->id;
        $company->bio = $validated['bio'];
        $company->website = $validated['website'];

        if (!$company->save($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('user.profile')->with('success', 'Operatiune executata cu succes.');
    }

    public function saveUserCompanyProfile(Request $request, $id)
    {
        $validated = $request->validate([
            'company_name' => 'required|min:2|max:255',
            'year_made' => 'nullable|numeric|min:1900',
            'phone' => 'nullable|numeric',
            'workers' => 'nullable|numeric|min:0',
            'cui' => 'nullable|numeric',
            'register_number' => 'nullable',
            'administrative_company' => 'nullable',
            'city_company' => 'nullable',
            'address_company' => 'nullable',
            'bio' => 'nullable',
            'website' => 'nullable',
        ]);

        $user = User::findOrFail($id);

        $validated['user_id'] = $user->id;

        if ($user->isCompany()) {
            $company = $user->company;
        } else {
            $company = new Company;
        }

        $company->name = $validated['company_name'];
        $company->year_made = $validated['year_made'];
        $company->phone = $validated['phone'];
        $company->workers = $validated['workers'];
        $company->cui = $validated['cui'];
        $company->register_number = $validated['register_number'];
        $company->administrative = $validated['administrative_company'];
        $company->city = $validated['city_company'];
        $company->address = $validated['address_company'];
        $company->user_id = $user->id;
        $company->bio = $validated['bio'];
        $company->website = $validated['website'];

        // check if company belongs to me
        // $user->can('update', $company);
        // auth()->user()->can('update', $company);
        $this->authorize('update', $company);

        if (!$company->save($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('user.default.profile', $user->id)->with('success', 'Operatiune executata cu succes.');
    }

    public function settings()
    {
        $categories = Category::all();
        $user = auth()->user();
        $my_categories = $user->getAssocCategories();

        if ($user->company) {
            $company = $user->company;
        } else {
            $company = new Company;
        }

        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.settings', [
            'categories' => $categories,
            'my_categories' => $my_categories,
            'user' => $user,
            'company' => $company,
        ]);

    }

    public function settings_vue()
    {
        $categories = Category::all();
        $user = auth()->user();
        $my_categories = $user->getAssocCategories();

        if ($user->company) {
            $company = $user->company;
        } else {
            $company = new Company;
        }

        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.settings-vue', [
            'categories' => $categories,
            'my_categories' => $my_categories,
            'user' => $user,
            'company' => $company,
        ]);

    }

    public function company_profile()
    {
        // $categories = Category::all();
        $user = auth()->user();
        // $my_categories = $user->getAssocCategories();

        if ($user->company) {
            $company = $user->company;
        } else {
            $company = new Company;
        }

        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.company', [
            // 'categories' => $categories,
            // 'my_categories' => $my_categories,
            'user' => $user,
            'company' => $company,
        ]);

    }

    public function settings_company()
    {
        // $categories = Category::all();
        $user = auth()->user();
        // $my_categories = $user->getAssocCategories();

        if ($user->company) {
            $company = $user->company;
        } else {
            $company = null;
        }

        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);settings-company

        return view('volgh.profile.settings-company', [
            'company' => $company,
        ]);

    }

    public function settings_pro()
    {
        // $categories = Category::all();
        $user = auth()->user();
        $user->user_name_profile;
        // $my_categories = $user->getAssocCategories();

        // dd($user->user_name_profile);

        if (!$user->isPro()) {
            return redirect()->back();
        }

        $user->makeHidden(['password', 'remember_token', 'stripe_id', 'card_brand', 'card_last_four']);

        // return view('profile.view', [
        //     'categories' => $categories,
        //     'my_categories' => $my_categories
        // ]);

        return view('volgh.profile.settings-pro', ['user' => $user]);

    }

    public function deletePhoto($id)
    {

        // Doar proprietar si DMIN pot sterge poza.

        $profile = Profile::findOrFail($id);

        // if($profile->profile_photo == "default-photo.png"){
        //     dd('daaa');
        // }

        // verifica profil curent apartine user curent
        $this->authorize('update', $profile);

        // dd($profile->profile_photo);

        if ($profile) {

            if ($profile->profile_photo == "default-photo.png") {
                return redirect()->back()->with('error', 'Eroare. Nu exista avatar.');
            } else {
                if (!unlink(public_path('images/avatars/' . $profile->profile_photo))) {
                    return redirect()->back()->with('error', 'Eroare. Va rugam sa incercati mai tarziu.');
                }

                if (public_path('images/avatars/thumbnails/' . $profile->profile_photo) !== null) {
                    unlink(public_path('images/avatars/thumbnails/' . $profile->profile_photo));
                }

                $profile->profile_photo = 'default-photo.png';
                $profile->save();
                return redirect()->route('user.profile')->with('success', 'Modificare efectuata cu succes.');
            }
        } else {
            return redirect()->back()->with('error', 'Eroare. Nu exista avatar.');
        }
    }

    public function personal_reviews()
    {
        return view('volgh.profile.reviews');
    }

}
