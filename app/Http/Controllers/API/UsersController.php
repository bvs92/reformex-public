<?php

namespace App\Http\Controllers\API;

use App\Badge;
use App\Credit;
use App\Http\Controllers\API\Controller;
use App\Notifications\AdminChangeUserAccount;
use App\Notifications\AdminChangeUserProAccount;
use App\Notifications\AdminChangeUserRoles;
use App\Notifications\AdminUserCompanyProfileUpdated;
use App\Notifications\AdminUserCreated;
use App\Notifications\AdminUserPasswordChange;
use App\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{

    public function getUsers()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $users = \App\User::all();

        $users = $users->filter(function ($item) use ($user) {
            if ($item->id !== $user->id) {
                return $item;
            }
        });

        $users->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);

        return response()->json(['users' => $users, 'total' => $user->count()]);
    }

    public function getUsersPros()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $users = \App\User::all();
        $users = $users->filter(function ($item) {
            if ($item->isPro()) {
                return $item;
            }
        });

        $users = $users->filter(function ($item) use ($user) {
            if ($item->id !== $user->id) {
                return $item;
            }
        });

        $users->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);

        return response()->json(['users' => $users, 'total' => $users->count()]);
    }

    public function getUserDemands($id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        return response()->json(['demands' => $user->demands, 'total' => $user->demands->count()]);
    }

    public function getUserUnlockedDemands($id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        if ($user->isPro()) {
            // $demands = \App\Demand::all();
            // $demands = $demands->filter(function ($item) {
            //     if ($item->isBought($user->professional->id)) {
            //         return $item;
            //     }
            // });

            $demands = $user->professional->demands;
        } else {
            $demands = [];
        }

        return response()->json(['demands' => $demands, 'total' => $demands->count()]);
    }

    public function get($id)
    {
        try {
            $user = \App\User::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);

        return response()->json(['user' => $user->only('first_name', 'last_name', 'email', 'username')]);
    }

    public function getUserCompany($id)
    {
        try {
            $user = \App\User::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $user->company->location;

        return response()->json(['success' => true, 'company' => $user->company]);
    }

    public function getCurrent()
    {

        try {
            $user = auth()->user();
            $user['is_pro'] = $user->isPro();
            $user['is_admin'] = $user->isAdmin();
            $user['complete_name'] = $user->getName();
            $user['profile_photo'] = $user->getFullProfilePhoto();
            $user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id', 'roles']);
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['auth_user' => $user]);

    }

    public function getModerators()
    {
        try {
            $moderators = \App\User::role(['admin', 'editor', 'moderator'])->get();

            $moderators->each(function ($item) {
                // $item->makeVisible(['role' => $item->getFirstRole()]);
                $item['profile_photo'] = $item->getFullProfilePhoto();
                $item->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);
            });
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$moderators) {
            $moderators = null;
        }

        return response()->json(['moderators' => $moderators]);
    }

    public function saveCompanyProfile(Request $request, $id)
    {

        try {
            $user = \App\User::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
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
            return response()->json(['validation_errors' => $validator->errors()], 401);
        }

        $validated = $validator->validated();

        // return response()->json($validated);

        $validated['user_id'] = auth()->user()->id;

        if ($user->isCompany()) {
            $company = $user->company;
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
        $company->user_id = $user->id;
        $company->company_type = $validated['company_type'];
        // $company->bio = $validated['bio'];
        // $company->website = $validated['website'];

        if (!$company->save($validated)) {
            return response()->json(['errors' => 'Am intampinat erori. Va rugam incercati mai tarziu.']);
        }

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

        Notification::send($user, new AdminUserCompanyProfileUpdated($user));

        return response()->json(['success' => 'Modificarile au fost efectuate cu succes.']);

    }

    public function storeUser(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3|alpha',
            'last_name' => 'required|min:3|alpha',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        $roles = Role::whereIn('name', $valid_request['roles'])->get();
        // return response()->json(['$valid_request' => $roles]);
        // return response()->json(['$valid_request' => $roles]);
        // return response()->json(['$valid_request' => $valid_request]);

        $user = new \App\User();
        $user->first_name = $valid_request['first_name'];
        $user->last_name = $valid_request['last_name'];
        $user->email = $valid_request['email'];

        $password_initial = Str::of(Str::uuid())->substr(0, 10); // generate random uuid and take the first 10 characters.
        // generate random password, send with email.
        $user->password = Hash::make($password_initial);
        $user->username = $this->generateUsername($valid_request['first_name'], $valid_request['last_name']);
        $user->status = 1;
        $user->assignRole($roles);

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        // if role == professional => activate PRO profile?

        $user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id', 'roles']);

        // check if welcome_user is true to send welcome email.

        // send email to user with password_initial
        Notification::send($user, new AdminUserCreated($user, $password_initial));

        return response()->json(['success' => 'Ok.', 'user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3|string',
            'last_name' => 'required|min:3|string',
            'username' => 'required|alpha_dash|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // $user = new \App\User();
        $user->first_name = $valid_request['first_name'];
        $user->last_name = $valid_request['last_name'];
        $user->email = $valid_request['email'];
        $user->username = \Illuminate\Support\Str::slug($valid_request['username'], '_');

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        $user->makeHidden(['password']);
        $user['is_pro'] = $user->isPro();

        // check if welcome_user is true to send welcome email.

        Notification::send($user, new AdminUserCompanyProfileUpdated($user));

        return response()->json(['success' => 'Ok.', 'user' => $user]);
    }

    public function generatePassword($id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $password_initial = Str::of(Str::uuid())->substr(0, 10); // generate random uuid and take the first 10 characters.
        // generate random password, send with email.
        $user->password = Hash::make($password_initial);

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        // send email to user with password_initial
        Notification::send($user, new AdminUserPasswordChange($user, $password_initial));

        return response()->json(['success' => 'Ok.']);
    }

    public function changePassword(Request $request, $id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:10|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // generate random password, send with email.
        $user->password = Hash::make($valid_request['password']);

        // return response()->json(['valid_request' => $valid_request['password'], '$user->password' => $user->password]);

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        // send email to user with password_initial
        Notification::send($user, new AdminUserPasswordChange($user, $valid_request['password']));

        return response()->json(['success' => 'Ok.']);
    }

    public function changeCurrentPassword(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:10|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // generate random password, send with email.
        $user->password = Hash::make($valid_request['password']);

        // return response()->json(['valid_request' => $valid_request['password'], '$user->password' => $user->password]);

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        return response()->json(['success' => true]);
    }

    public function toggleAccountStatus()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if ($user->status == 1) {
            $user->status = 0;
        }

        // return response()->json(['valid_request' => $valid_request['password'], '$user->password' => $user->password]);

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        return response()->json(['success' => true]);
    }

    public function updatePersonalInformation(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $validator = Validator::make($request->all(), [
            'last_name' => 'required|min:2|string',
            'first_name' => 'required|min:2|string',
            'email' => 'required|email|min:4|string|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // generate random password, send with email.
        $user->last_name = $valid_request['last_name'];
        $user->first_name = $valid_request['first_name'];
        $user->email = $valid_request['email'];

        // return response()->json(['valid_request' => $valid_request['password'], '$user->password' => $user->password]);

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        $user->makeHidden(['card_brand', 'card_last_four', 'status', 'stripe_id']);

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function changeStatus($id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        if ($user->status == 1) {
            $user->status = 0;
        } else {
            $user->status = 1;
        }

        // return response()->json(['valid_request' => $valid_request['password'], '$user->password' => $user->password]);

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        $user->makeHidden(['password']);
        $user['is_pro'] = $user->isPro();
        $user->roles->makeHidden(['pivot', 'guard_name']);
        $user['roles'] = $user->roles;
        $user->badge;
        $user['is_email_verified'] = $user->email_verified_at;

        // send email to user
        if ($user->status == 1) {
            Notification::send($user, new AdminChangeUserAccount($user, true));
        } else {
            Notification::send($user, new AdminChangeUserAccount($user, false));
        }

        return response()->json(['success' => 'Ok.', 'user' => $user]);
    }

    public function changeEmailStatus($id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        if ($user->isEmailVerified()) {
            $user->email_verified_at = null;
        } else {
            $user->email_verified_at = \Carbon\Carbon::now();
        }

        // return response()->json(['valid_request' => $valid_request['password'], '$user->password' => $user->password]);

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        $user->makeHidden(['password']);
        $user['is_pro'] = $user->isPro();
        $user->roles->makeHidden(['pivot', 'guard_name']);
        $user['roles'] = $user->roles;
        $user->badge;
        $user['is_email_verified'] = $user->email_verified_at;

        // send email to user
        // if ($user->status == 1) {
        //     Notification::send($user, new AdminChangeUserAccount($user, true));
        // } else {
        //     Notification::send($user, new AdminChangeUserAccount($user, false));
        // }

        return response()->json(['success' => 'Ok.', 'user' => $user]);
    }

    public function updateUserRoles(Request $request, $id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'roles' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        // return response()->json(['$valid_request' => $valid_request]);

        // $user = new \App\User();
        $user->roles()->sync($valid_request['roles']);

        if (!$user->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        $user->makeHidden(['password']);
        $user['is_pro'] = $user->isPro();
        $user->roles->makeHidden(['pivot', 'guard_name']);
        $user['roles'] = $user->roles;

        Notification::send($user, new AdminChangeUserRoles($user));

        return response()->json(['success' => 'Ok.', 'user' => $user]);
    }

    public function deleteAccount()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        DB::beginTransaction();

        if ($user->profile) {

            if ($user->profile->profile_photo && $user->profile->profile_photo != 'default-photo.png') {

                if (file_exists(public_path('images/avatars/' . $user->profile->profile_photo))) {
                    if (!unlink(public_path('images/avatars/' . $user->profile->profile_photo))) {
                        DB::rollback();
                        return response()->json(['errors' => 'a - true']);
                    }
                }

                if (file_exists(public_path('images/avatars/thumbnails/' . $user->profile->profile_photo))) {
                    if (!unlink(public_path('images/avatars/thumbnails/' . $user->profile->profile_photo))) {
                        DB::rollback();
                        return response()->json(['errors' => 'b- true']);
                    }
                }

            }

            if (!$user->profile()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'c- true']);
            }
        }

        if ($user->company) {
            if ($user->company->location) {
                if (!$user->company->location()->delete()) {
                    DB::rollback();
                    return response()->json(['errors' => 'd- true']);
                }
            }

            if (!$user->company()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'e- true']);
            }
        }

        if ($user->professional) {
            if (!$user->professional()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'f- true']);
            }
        }

        if ($user->credit) {
            if (!$user->credit()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'g - true']);
            }
        }

        // if ($user->transactions) {
        //     $user->transactions()->delete();
        // }

        if ($user->activities && $user->activities()->count() > 0) {
            if (!$user->activities()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'h- true']);
            }
        }

        if ($user->roles && $user->roles()->count() > 0) {
            if (!$user->roles()->detach()) {
                DB::rollback();
                return response()->json(['errors' => 'i - true']);
            }
        }

        if ($user->judets && $user->judets()->count() > 0) {
            if (!$user->judets()->detach()) {
                DB::rollback();
                return response()->json(['errors' => 'j - true']);
            }
        }

        if ($user->badge) {
            if (!$user->badge()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'k - true']);
            }
        }

        if ($user->public_profile) {
            if (!$user->public_profile()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'l - true']);
            }
        }

        if ($user->user_name_profile) {
            if (!$user->user_name_profile()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'm - true']);
            }
        }

        if ($user->demands_bought && $user->demands_bought()->count() > 0) {
            if (!$user->demands_bought()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'n - true']);
            }
        }

        if ($user->coupons && $user->coupons()->count() > 0) {
            if (!$user->coupons()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'o - true']);
            }
        }

        if ($user->coupons_requests && $user->coupons_requests()->count() > 0) {
            if (!$user->coupons_requests()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'p - true']);
            }
        }

        if ($user->social_profiles && $user->social_profiles()->count() > 0) {
            if (!$user->social_profiles()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'q- true']);
            }
        }

        if ($user->notifications && $user->notifications()->count() > 0) {
            if (!$user->notifications()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'r- true']);
            }
        }

        if ($user->projects && $user->projects()->count() > 0) {

            foreach ($user->projects as $project) {

                if ($project->photos && $project->photos->count() > 0) {
                    foreach ($project->photos as $photo) {
                        if (!$photo->delete()) {
                            DB::rollBack();
                            return response()->json(['errors' => 's - true']);
                        }
                    }
                }

                if ($project->categories && $project->categories->count() > 0) {
                    $project->categories()->detach();
                }

                if (!$project->delete()) {
                    DB::rollBack();
                    return response()->json(['errors' => 't- true']);
                }

            }

        }

        if ($user->reports && $user->reports()->count() > 0) {
            if (!$user->reports()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'u- true']);
            }
        }

        // if ($user->demands) {
        //     $user->demands()->delete();
        // }

        // if ($user->quotes) {
        //     $user->quotes()->delete();
        // }

        // if ($user->quote_files) {
        //     $user->quote_files()->delete();
        // }

        // if ($user->client_messages) {
        //     $user->client_messages()->delete();
        // }

        // if ($user->client_message_files) {
        //     $user->client_message_files()->delete();
        // }

        // if ($user->tickets) {
        //     $user->tickets()->delete();
        // }

        // if ($user->response_tickets) {
        //     $user->response_tickets()->delete();
        // }

        // if ($user->ticket_files) {
        //     $user->ticket_files()->delete();
        // }

        // if ($user->timelines) {
        //     $user->timelines()->delete();
        // }

        // if ($user->payments) {
        //     $user->payments()->delete();
        // }

        // if ($user->refundsDemand) {
        //     $user->refundsDemand()->delete();
        // }

        // if ($user->social_profiles) {
        //     $user->social_profiles()->delete();
        // }

        // if ($user->projects) {
        //     $user->projects()->delete();
        // }

        // if ($user->reports) {
        //     $user->reports()->delete();
        // }

        // if ($user->ticket_actions) {
        //     $user->ticket_actions()->delete();
        // }

        if (!$user->delete()) {
            DB::rollback();
            return response()->json(['errors' => 'v- true']);
        }

        DB::commit();

        return response()->json(['success' => true]);
    }

    public function deleteUser($id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        DB::beginTransaction();

        if ($user->profile) {

            if ($user->profile->profile_photo && $user->profile->profile_photo != 'default-photo.png') {

                if (file_exists(public_path('images/avatars/' . $user->profile->profile_photo))) {
                    if (!unlink(public_path('images/avatars/' . $user->profile->profile_photo))) {
                        DB::rollback();
                        return response()->json(['errors' => 'a - true']);
                    }
                }

                if (file_exists(public_path('images/avatars/thumbnails/' . $user->profile->profile_photo))) {
                    if (!unlink(public_path('images/avatars/thumbnails/' . $user->profile->profile_photo))) {
                        DB::rollback();
                        return response()->json(['errors' => 'b- true']);
                    }
                }

            }

            if (!$user->profile()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'c- true']);
            }
        }

        if ($user->company) {
            if ($user->company->location) {
                if (!$user->company->location()->delete()) {
                    DB::rollback();
                    return response()->json(['errors' => 'd- true']);
                }
            }

            if (!$user->company()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'e- true']);
            }
        }

        if ($user->professional) {
            if (!$user->professional()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'f- true']);
            }
        }

        if ($user->credit) {
            if (!$user->credit()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'g - true']);
            }
        }

        // if ($user->transactions) {
        //     $user->transactions()->delete();
        // }

        if ($user->activities && $user->activities()->count() > 0) {
            if (!$user->activities()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'h- true']);
            }
        }

        if ($user->roles && $user->roles()->count() > 0) {
            if (!$user->roles()->detach()) {
                DB::rollback();
                return response()->json(['errors' => 'i - true']);
            }
        }

        if ($user->judets && $user->judets()->count() > 0) {
            if (!$user->judets()->detach()) {
                DB::rollback();
                return response()->json(['errors' => 'j - true']);
            }
        }

        if ($user->badge) {
            if (!$user->badge()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'k - true']);
            }
        }

        if ($user->public_profile) {
            if (!$user->public_profile()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'l - true']);
            }
        }

        if ($user->user_name_profile) {
            if (!$user->user_name_profile()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'm - true']);
            }
        }

        if ($user->demands_bought && $user->demands_bought()->count() > 0) {
            if (!$user->demands_bought()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'n - true']);
            }
        }

        if ($user->coupons && $user->coupons()->count() > 0) {
            if (!$user->coupons()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'o - true']);
            }
        }

        if ($user->coupons_requests && $user->coupons_requests()->count() > 0) {
            if (!$user->coupons_requests()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'p - true']);
            }
        }

        if ($user->social_profiles && $user->social_profiles()->count() > 0) {
            if (!$user->social_profiles()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'q- true']);
            }
        }

        if ($user->notifications && $user->notifications()->count() > 0) {
            if (!$user->notifications()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'r- true']);
            }
        }

        if ($user->projects && $user->projects()->count() > 0) {

            foreach ($user->projects as $project) {

                if ($project->photos && $project->photos->count() > 0) {
                    foreach ($project->photos as $photo) {
                        if (!$photo->delete()) {
                            DB::rollBack();
                            return response()->json(['errors' => 's - true']);
                        }
                    }
                }

                if ($project->categories && $project->categories->count() > 0) {
                    $project->categories()->detach();
                }

                if (!$project->delete()) {
                    DB::rollBack();
                    return response()->json(['errors' => 't- true']);
                }

            }

        }

        if ($user->reports && $user->reports()->count() > 0) {
            if (!$user->reports()->delete()) {
                DB::rollback();
                return response()->json(['errors' => 'u- true']);
            }
        }

        // if ($user->demands) {
        //     $user->demands()->delete();
        // }

        // if ($user->quotes) {
        //     $user->quotes()->delete();
        // }

        // if ($user->quote_files) {
        //     $user->quote_files()->delete();
        // }

        // if ($user->client_messages) {
        //     $user->client_messages()->delete();
        // }

        // if ($user->client_message_files) {
        //     $user->client_message_files()->delete();
        // }

        // if ($user->tickets) {
        //     $user->tickets()->delete();
        // }

        // if ($user->response_tickets) {
        //     $user->response_tickets()->delete();
        // }

        // if ($user->ticket_files) {
        //     $user->ticket_files()->delete();
        // }

        // if ($user->timelines) {
        //     $user->timelines()->delete();
        // }

        // if ($user->payments) {
        //     $user->payments()->delete();
        // }

        // if ($user->refundsDemand) {
        //     $user->refundsDemand()->delete();
        // }

        // if ($user->social_profiles) {
        //     $user->social_profiles()->delete();
        // }

        // if ($user->projects) {
        //     $user->projects()->delete();
        // }

        // if ($user->reports) {
        //     $user->reports()->delete();
        // }

        // if ($user->ticket_actions) {
        //     $user->ticket_actions()->delete();
        // }

        if (!$user->delete()) {
            DB::rollback();
            return response()->json(['errors' => 'v- true']);
        }

        DB::commit();

        return response()->json(['success' => true]);
    }

    public function activateProAccount($id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        if ($user->isPro()) {
            return response()->json(['errors' => 'Utilizatorul este deja profesionist.']);
        }

        if (!Professional::create([
            'user_id' => $user->id,
        ])) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        // Creare profil Credit
        if (!$user->credit) {
            Credit::create([
                'user_id' => $user->id,
                'amount' => 0,
            ]);
        }

        if (!$user->badge) {
            Badge::create([
                'user_id' => $user->id,
                'verified' => true,
            ]);
        } else {
            if (!$user->badge->verified) {
                $user->badge->update(['verified' => true]);
            }
        }

        // Adauga rol de professional
        if (!$user->hasRole('professional')) {
            $role = Role::where('name', 'professional')->first();
            $user->assignRole($role);
        }

        $user->makeHidden(['password']);
        $user['is_pro'] = true;
        // $user->roles->makeHidden(['pivot', 'guard_name']);
        // $user['roles'] = $user->roles;

        // send email to user
        Notification::send($user, new AdminChangeUserProAccount($user, true));

        return response()->json(['success' => 'Ok.', 'user' => $user]);
    }

    public function desactivateProAccount($id)
    {
        try {
            $user = \App\User::find($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Utilizatorul nu este profesionist.']);
        }

        // Adauga rol de professional
        if (!$user->hasRole('professional')) {
            $user->removeRole('professional');
        }

        $user->professional()->delete();

        $user->makeHidden(['password']);
        $user['is_pro'] = false;
        // $user->roles->makeHidden(['pivot', 'guard_name']);
        // $user['roles'] = $user->roles;

        // send email to user
        Notification::send($user, new AdminChangeUserProAccount($user, false));

        return response()->json(['success' => 'Ok.', 'user' => $user]);
    }

    private function generateUsername($firstname, $lastname)
    {
        $last_name = \Illuminate\Support\Str::slug($lastname, '-');
        $first_name = \Illuminate\Support\Str::slug($firstname, '-');
        $username = $last_name . '-' . $first_name;

        while (\App\User::where('username', $username)->get()->count() > 0) {
            // regenereaza cu un alt timestamp
            $username = $last_name . '-' . $first_name . '-' . time();
        }

        return $username;
    }

}
