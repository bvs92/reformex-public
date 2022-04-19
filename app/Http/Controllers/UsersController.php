<?php

namespace App\Http\Controllers;

use App\Mail\NewUserCreatedMail;
use App\Mail\UserPasswordUpdateMail;
use App\Professional;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:admin']);
        // $this->middleware(['roles-exists', 'role:admin']);
    }

    public function all()
    {
        return view('volgh.users.all');
    }

    public function allPros()
    {
        return view('volgh.users.pros');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(20);
        $professionals = Professional::all();
        $total_users = User::all()->count();
        return view('volgh.users.index', compact(['users', 'professionals', 'total_users']));
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $permissions = Permission::all();
        $roles = Role::all();
        return view('users.show', compact(['user', 'permissions', 'roles']));
    }

    public function showForAdmin($id)
    {
        $user = \App\User::findOrFail($id);
        $user->makeHidden(['password']);
        $user['is_pro'] = $user->isPro();
        $user->roles->makeHidden(['pivot', 'guard_name']);
        $user['roles'] = $user->roles;
        $user->badge;
        $user['is_email_verified'] = $user->isEmailVerified();
        $user->public_profile;
        $user->user_name_profile;
        // dd($user);
        return view('volgh.users.show-admin', compact(['user']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // $subscriptions = Subscription::all();
        // $subscriptions->pluck('name', 'id');
        // dd($subscriptions->flatten());

        // dd($user->subscriptions->first());
        // if($subscriptions->contains($user->firstSubscription()))
        //     dd("da");
        // else
        //     dd("nu");

        return view('users.edit', compact(['user', 'subscriptions']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // verifica daca este admin

        // De folosit Event?
        // Elimina abonamente
        // $user->subscriptions()->detach();

        // Sterge cereri
        // $user->demands()->delete();

        // Sterge cotatii
        $user->quotes()->delete();

        // Delete user profile if exists.
        if ($user->profile) {
            // unlink(public_path('images/avatars/' . $user->profile->profile_photo));
            $user->profile()->delete();
        }

        if (!$user->delete()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('users.index')->with('success', 'Utilizator eliminat.');
    }

    // Added new

    // public function showUser(User $user)
    // {
    //     return view('users.show', compact('user'));
    // }

    // public function editUser(User $user)
    // {
    //     return view('users.edit', compact('user'));
    // }

    // Admin can cnage user's password
    public function adminChangePassword(Request $request, User $user)
    {
        // $user = User::findOrFail($id);

        $validated = $request->validate([
            'password' => 'required|min:6|max:255',
        ]);

        $password = Hash::make($validated['password']);

        $user->password = $password;

        if (!$user->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        // Trimite email catre utilizator
        Mail::to($user)->send(new UserPasswordUpdateMail($validated['password'], $user));

        return redirect()->route('user.admin.profile.edit', $user->id)->with('success', 'Parola modificata cu succes.');
    }

    // Admin can update user's profile
    public function adminUpdateUserProfile(Request $request, User $user)
    {

        // dd($user);
        // Verifica daca este admin

        $validated = $request->validate([
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if (!$user->update($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('user.admin.profile.edit', $user->id)->with('success', 'Profil modificat cu succes.');

    }

    public function adminUpdateUserPhoto(Request $request, User $user)
    {

        $validated = $request->validate([
            'profile_photo' => 'required|image|max:100000000',
        ]);

        if ($request->hasFile('profile_photo')) {

            $file = $validated['profile_photo'];

            // Get image original extension
            $ext = $file->getClientOriginalExtension();

            // Compose the name
            $file_name = 'profile-' . time() . "-" . $user->id . '.' . $ext;

            $data['user_id'] = $user->id;
            $data['profile_photo'] = $file_name;

            // Check if user has profile & do the corresponding actions. Create one Or edit an existing.
            if (!$user->profile) {

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
                return redirect()->route('user.admin.profile.edit', $user->id)->with('success', 'Modificare efectuata cu succes.');

            } else {
                $current_profile = $user->profile;

                // 1. Delete existing photo (if any)

                // if($current_profile->profile_photo && $current_profile->profile_photo != 'default-photo.png'){
                //     unlink(public_path('images/avatars/' . $current_profile->profile_photo));
                // }

                if ($current_profile->profile_photo && $current_profile->profile_photo != 'default-photo.png') {

                    if (public_path('images/avatars/' . $current_profile->profile_photo) !== null) {
                        unlink(public_path('images/avatars/' . $current_profile->profile_photo));
                    }

                    if (public_path('images/avatars/thumbnails/' . $current_profile->profile_photo) !== null) {
                        unlink(public_path('images/avatars/thumbnails/' . $current_profile->profile_photo));
                    }

                }

                // 2. Upload file

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

                if (!$user->profile()->update($data)) {
                    return redirect()->back()->with('error', 'Eroare. Va rugam sa incercati mai tarziu.');
                }
                return redirect()->route('user.admin.profile.edit', $user->id)->with('success', 'Modificare efectuata cu succes.');
            } // end if

        }

    }

    // Admin can delete phofile photo
    public function adminDeleteUserPhoto($id)
    {
        $profile = Profile::findOrFail($id);

        // if($profile->profile_photo == "default-photo.png"){
        //     dd('daaa');
        // }

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
                return redirect()->route('user.admin.profile.edit', $profile->user->id)->with('success', 'Modificare efectuata cu succes.');
            }
        } else {
            return redirect()->back()->with('error', 'Eroare. Nu exista avatar.');
        }
    }

    // Admin can update user's subscription

    public function adminUpdateSubscription(Request $request, User $user)
    {
        $validated = $request->validate([
            'subscription' => 'required|exists:subscriptions,id',
        ]);

        // dd(collect($validated['subscription'])->count());

        if (collect($validated['subscription'])->count() > 1) {
            return redirect()->back()->with('error', 'Este permis un singur abonament.');
        }

        if (!$user->subscriptions()->sync($validated['subscription'])) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('users.edit', $user)->with('success', 'Abonament modificat cu succes.');
    }

    // Admin deletes user

    // Associate user with permissions
    public function giveUserPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|exists:permissions,id',
        ]);

        // Associate with role
        $user->syncPermissions($validated);

        return redirect()->route('users.show', $user->id)->with('success', 'Operatie efectuata cu succes.');
    }

    public function resetUserPermissions($id)
    {
        $user = User::findOrFail($id);

        $user->permissions()->detach();

        return redirect()->route('users.show', $user->id)->with('success', 'Operatie efectuata cu succes.');
    }

    // Associate user with roles
    public function giveUserRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|exists:roles,id',
        ]);

        // Associate with role
        $user->syncRoles($validated);

        return redirect()->route('user.default.profile', $user->id)->with('success', 'Operatie efectuata cu succes.');
    }

    public function resetUserRoles($id)
    {
        $user = User::findOrFail($id);

        $user->roles()->detach();

        return redirect()->route('users.show', $user->id)->with('success', 'Operatie efectuata cu succes.');
    }

    public function adminChangeStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->status == '1') {
            $user->status = '0';
        } else {
            $user->status = '1';
        }

        if (!$user->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('user.admin.profile.edit', $user->id)->with('success', 'Operatie efectuata cu succes.');
    }

    public function adminCreateNewUserView()
    {
        return view('volgh.users.create');
    }

    public function adminCreateNewUser(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|string|email|unique:users',
        ]);

        // Generate random characters password
        $random_c = md5(rand(0, 9));
        $password = substr($random_c, 0, 10); // 10 caractere random

        $username = $this->generateUsername($validated['first_name'], $validated['last_name']);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($password), // de schimbat cu $password
        ]);

        $user->assignRole('standard');

        // Trimite email catre utilizator
        Mail::to($user)->send(new NewUserCreatedMail($password, $user));

        return redirect()->route('users.index')->with('success', 'Operatiune efectuata cu succes.');
    }

    private function generateUsername($firstname, $lastname)
    {
        $last_name = \Illuminate\Support\Str::slug($lastname, '_');
        $first_name = \Illuminate\Support\Str::slug($firstname, '_');
        $username = $last_name . '_' . $first_name . '_' . time();

        while (\App\User::where('username', $username)->get()->count() > 0) {
            // regenereaza cu un alt timestamp
            $username = $last_name . '_' . $first_name . '_' . time();
        }

        return $username;
    }

}
