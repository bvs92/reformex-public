<?php

namespace App\Http\Controllers\API;

use App\Badge;
use App\Credit;
use App\Http\Controllers\Controller;
use App\NotificationSettings;
use App\Notifications\AdminProCreatedNotification;
use App\Notifications\companies\MarkCompanyAsUnverified;
use App\Notifications\companies\MarkCompanyAsVerified;
use App\Notifications\RegisterCompanyFormNotification;
use App\Notifications\SendDirectDemandToCompanyNotification;
use App\Notifications\UserRegistrationDecision;
use App\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class CompaniesController extends Controller
{

    public function getCurrentCompany()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $user->company->location;

        return response()->json(['success' => true, 'company' => $user->company]);
    }

    public function registerForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'owner_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'cui' => 'required',
            'register_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_form = $validator->valid();

        Notification::route('mail', 'contact@reformex.ro')->notify(new RegisterCompanyFormNotification($validated_form));

        return response()->json(['success' => true]);
    }

    public function sendFormMessageToCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'city' => 'required',
            'message' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_form = $validator->valid();

        $user = \App\User::find($validated_form['user_id']);

        if (!$user) {
            return response()->json(['errors' => true]);
        }

        Notification::route('mail', $user->email)->notify(new SendDirectDemandToCompanyNotification($validated_form));

        return response()->json(['success' => true]);
    }

    public function searchCompanies($category_uuid, $location_code, $page = 1)
    {
        $perPage = 10;
        if ($category_uuid == 'all' || $category_uuid == null || $category_uuid == '') {
            // $category = \App\Category::all();

            if ($location_code == 'all' || $location_code == null || $location_code == '') {
                // $location = \App\Judet::all();
                $pros = \App\Professional::all();

                // get only active users
                $pros = $pros->filter(function ($item) {
                    if ($item->user->status == 1) {
                        return $item;
                    }
                });

                $companies = $pros->map(function ($item) {
                    $item->user;
                    $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                    $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                    $item->user->badge;
                    $item->user->company;
                    $item->user->company->card;
                    $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                    $item->user->categories = $item->user->getFirst3Categories();
                    $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                    $item->user->company->location;
                    // $item->user->public_profile;
                    $item->user->user_name_profile;
                    return $item;
                });

                $total_results = $companies->count();

                if ($companies->count() > 0) {
                    $companies = $companies->forPage($page, $perPage)->all();
                } else {
                    $companies = [];
                }

                $total_pages = ceil($total_results / $perPage);
                if ($page > $total_pages) {
                    $page = 1;
                }

                return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
            } else {
                $location = \App\Judet::where('code', $location_code)->first();
                // return response()->json(['category all, locations is' => $location]);

                if (!$location) {
                    $pros = \App\Professional::all();

                    if (!$pros || $pros->count() == 0) {
                        return response()->json(['total_pages' => 0, 'companies' => [], 'total_results' => 0]);
                    }

                    // get only active users
                    $pros = $pros->filter(function ($item) {
                        if ($item->user->status == 1) {
                            return $item;
                        }
                    });

                    $companies = $pros->map(function ($item) {
                        $item->user;
                        $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                        $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                        $item->user->badge;
                        $item->user->company;
                        $item->user->company->card;
                        $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                        $item->user->categories = $item->user->getFirst3Categories();
                        $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                        $item->user->company->location;
                        // $item->user->public_profile;
                        $item->user->user_name_profile;
                        return $item;
                    });

                    $total_results = $companies->count();

                    if ($companies->count() > 0) {
                        $companies = $companies->forPage($page, $perPage)->all();
                    } else {
                        $companies = [];
                    }

                    $total_pages = ceil($total_results / $perPage);
                    if ($page > $total_pages) {
                        $page = 1;
                    }

                    return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
                }

                // get companies from location
                $pros = \App\Professional::all();

                // get only active users
                $pros = $pros->filter(function ($item) {
                    if ($item->user->status == 1) {
                        return $item;
                    }
                });

                $companies = $pros->filter(function ($item) use ($location) {
                    if ($item->user->judets->contains($location)) {
                        return $item;
                    }
                });

                $companies = $companies->map(function ($item) {
                    $item->user;
                    $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                    $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                    $item->user->badge;
                    $item->user->company;
                    $item->user->company->card;
                    $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                    $item->user->categories = $item->user->getFirst3Categories();
                    $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                    $item->user->company->location;
                    // $item->user->public_profile;
                    $item->user->user_name_profile;
                    return $item;
                });

                $total_results = $companies->count();

                if ($companies->count() > 0) {
                    $companies = $companies->forPage($page, $perPage)->all();
                } else {
                    $companies = [];
                }

                $total_pages = ceil($total_results / $perPage);
                if ($page > $total_pages) {
                    $page = 1;
                }

                return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
            }

        } else {
            $category = \App\Category::where('uuid', $category_uuid)->first();

            if (!$category) {
                return response()->json(['companies' => [], 'total_page' => 1, 'total_results' => 0]);
            }

            if ($location_code == 'all' || $location_code == null || $location_code == '') {
                // return response()->json(['location is all, category is' => $category]);
                $pros = \App\Professional::all();

                // get only active users
                $pros = $pros->filter(function ($item) {
                    if ($item->user->status == 1) {
                        return $item;
                    }
                });

                $companies = $pros->filter(function ($item) use ($category) {
                    if ($item->categories->contains($category)) {
                        return $item;
                    }
                });

                $companies = $companies->map(function ($item) {
                    $item->user;
                    $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                    $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                    $item->user->badge;
                    $item->user->company;
                    $item->user->company->card;
                    $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                    $item->user->categories = $item->user->getFirst3Categories();
                    $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                    $item->user->company->location;
                    // $item->user->public_profile;
                    $item->user->user_name_profile;
                    return $item;
                });

                $total_results = $companies->count();

                if ($companies->count() > 0) {
                    $companies = $companies->forPage($page, $perPage)->all();
                } else {
                    $companies = [];
                }

                $total_pages = ceil($total_results / $perPage);
                if ($page > $total_pages) {
                    $page = 1;
                }

                return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
            } else {
                $location = \App\Judet::where('code', $location_code)->first();
                // return response()->json(['location' => $location, 'category' => $category]);

                if (!$location) {
                    $pros = \App\Professional::all();

                    // get only active users
                    $pros = $pros->filter(function ($item) {
                        if ($item->user->status == 1) {
                            return $item;
                        }
                    });

                    $companies = $pros->filter(function ($item) use ($category) {
                        if ($item->categories && $item->categories->contains($category)) {
                            return $item;
                        }
                    });

                    $companies = $companies->map(function ($item) {
                        $item->user;
                        $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                        $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                        $item->user->badge;
                        $item->user->company;
                        $item->user->company->card;
                        $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                        $item->user->categories = $item->user->getFirst3Categories();
                        $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                        $item->user->company->location;
                        // $item->user->public_profile;
                        $item->user->user_name_profile;
                        return $item;
                    });

                    $total_results = $companies->count();

                    if ($companies->count() > 0) {
                        $companies = $companies->forPage($page, $perPage)->all();
                    } else {
                        $companies = [];
                    }

                    $total_pages = ceil($total_results / $perPage);
                    if ($page > $total_pages) {
                        $page = 1;
                    }

                    return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
                } else {

                    // get companies from location
                    $pros = \App\Professional::all();

                    // get only active users
                    $pros = $pros->filter(function ($item) {
                        if ($item->user->status == 1) {
                            return $item;
                        }
                    });

                    $companies = $pros->filter(function ($item) use ($location) {
                        if ($item->user->judets->contains($location)) {
                            return $item;
                        }
                    });

                    $companies = $companies->filter(function ($item) use ($category) {
                        if ($item->categories && $item->categories->contains($category)) {
                            return $item;
                        }
                    });

                    $companies = $companies->map(function ($item) {
                        $item->user;
                        $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                        $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                        $item->user->badge;
                        $item->user->company;
                        $item->user->company->card;
                        $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                        $item->user->categories = $item->user->getFirst3Categories();
                        $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                        $item->user->company->location;
                        // $item->user->public_profile;
                        $item->user->user_name_profile;

                        return $item;
                    });

                    $total_results = $companies->count();

                    if ($companies->count() > 0) {
                        $companies = $companies->forPage($page, $perPage)->all();
                    } else {
                        $companies = [];
                    }

                    $total_pages = ceil($total_results / $perPage);
                    if ($page > $total_pages) {
                        $page = 1;
                    }

                    return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
                }

            }
        }

    }
    public function searchVerifiedCompanies($category_uuid, $location_code, $page = 1)
    {
        $perPage = 10;
        if ($category_uuid == 'all' || $category_uuid == null || $category_uuid == '') {
            // $category = \App\Category::all();

            if ($location_code == 'all' || $location_code == null || $location_code == '') {
                // $location = \App\Judet::all();
                $pros = \App\Professional::all();

                // get only active users
                $pros = $pros->filter(function ($item) {
                    if ($item->user->status == 1) {
                        return $item;
                    }
                });

                $pros = $pros->filter(function ($item) {
                    if ($item->user->badge) {
                        if ($item->user->badge->verified == 1) {
                            return $item;
                        }

                    }
                });

                if (!$pros || $pros->count() == 0) {
                    return response()->json(['total_pages' => 0, 'companies' => [], 'total_results' => 0]);
                }

                $companies = $pros->map(function ($item) {
                    $item->user;
                    $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                    $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                    $item->user->badge;
                    $item->user->company;
                    $item->user->company->card;
                    $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                    $item->user->categories = $item->user->getFirst3Categories();
                    $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                    $item->user->company->location;
                    // $item->user->public_profile;
                    $item->user->user_name_profile;
                    return $item;
                });

                $total_results = $companies->count();

                if ($companies->count() > 0) {
                    $companies = $companies->forPage($page, $perPage)->all();
                } else {
                    $companies = [];
                }

                $total_pages = ceil($total_results / $perPage);
                if ($page > $total_pages) {
                    $page = 1;
                }

                return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
            } else {
                $location = \App\Judet::where('code', $location_code)->first();
                // return response()->json(['category all, locations is' => $location]);

                if (!$location) {
                    $pros = \App\Professional::all();

                    if (!$pros || $pros->count() == 0) {
                        return response()->json(['total_pages' => 0, 'companies' => [], 'total_results' => 0]);
                    }

                    // get only active users
                    $pros = $pros->filter(function ($item) {
                        if ($item->user->status == 1) {
                            return $item;
                        }
                    });

                    $pros = $pros->filter(function ($item) {
                        if ($item->user->badge) {
                            if ($item->user->badge->verified == 1) {
                                return $item;
                            }

                        }
                    });

                    $companies = $pros->map(function ($item) {
                        $item->user;
                        $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                        $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                        $item->user->badge;
                        $item->user->company;
                        $item->user->company->card;
                        $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                        $item->user->categories = $item->user->getFirst3Categories();
                        $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                        $item->user->company->location;
                        // $item->user->public_profile;
                        $item->user->user_name_profile;
                        return $item;
                    });

                    $total_results = $companies->count();

                    if ($companies->count() > 0) {
                        $companies = $companies->forPage($page, $perPage)->all();
                    } else {
                        $companies = [];
                    }

                    $total_pages = ceil($total_results / $perPage);
                    if ($page > $total_pages) {
                        $page = 1;
                    }

                    return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
                }

                // get companies from location
                $pros = \App\Professional::all();

                if (!$pros || $pros->count() == 0) {
                    return response()->json(['total_pages' => 0, 'companies' => [], 'total_results' => 0]);
                }

                // get only active users
                $pros = $pros->filter(function ($item) {
                    if ($item->user->status == 1) {
                        return $item;
                    }
                });

                $pros = $pros->filter(function ($item) {
                    if ($item->user->badge && $item->user->badge->verified == 1) {
                        return $item;
                    }
                });

                $companies = $pros->filter(function ($item) use ($location) {
                    if ($item->user->judets->contains($location)) {
                        return $item;
                    }
                });

                $companies = $companies->map(function ($item) {
                    $item->user;
                    $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                    $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                    $item->user->badge;
                    $item->user->company;
                    $item->user->company->card;
                    $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                    $item->user->categories = $item->user->getFirst3Categories();
                    $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                    $item->user->company->location;
                    // $item->user->public_profile;
                    $item->user->user_name_profile;
                    return $item;
                });

                $total_results = $companies->count();

                if ($companies->count() > 0) {
                    $companies = $companies->forPage($page, $perPage)->all();
                } else {
                    $companies = [];
                }

                $total_pages = ceil($total_results / $perPage);
                if ($page > $total_pages) {
                    $page = 1;
                }

                return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
            }

        } else {
            $category = \App\Category::where('uuid', $category_uuid)->first();

            if (!$category) {
                return response()->json(['companies' => [], 'total_page' => 1, 'total_results' => 0]);
            }

            if ($location_code == 'all' || $location_code == null || $location_code == '') {
                // return response()->json(['location is all, category is' => $category]);
                $pros = \App\Professional::all();

                if (!$pros || $pros->count() == 0) {
                    return response()->json(['total_pages' => 0, 'companies' => [], 'total_results' => 0]);
                }

                // get only active users
                $pros = $pros->filter(function ($item) {
                    if ($item->user->status == 1) {
                        return $item;
                    }
                });

                $pros = $pros->filter(function ($item) {
                    if ($item->user->badge && $item->user->badge->verified == 1) {
                        return $item;
                    }
                });

                $companies = $pros->filter(function ($item) use ($category) {
                    if ($item->categories->contains($category)) {
                        return $item;
                    }
                });

                $companies = $companies->map(function ($item) {
                    $item->user;
                    $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                    $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                    $item->user->badge;
                    $item->user->company;
                    $item->user->company->card;
                    $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                    $item->user->categories = $item->user->getFirst3Categories();
                    $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                    $item->user->company->location;
                    // $item->user->public_profile;
                    $item->user->user_name_profile;
                    return $item;
                });

                $total_results = $companies->count();

                if ($companies->count() > 0) {
                    $companies = $companies->forPage($page, $perPage)->all();
                } else {
                    $companies = [];
                }

                $total_pages = ceil($total_results / $perPage);
                if ($page > $total_pages) {
                    $page = 1;
                }

                return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
            } else {
                $location = \App\Judet::where('code', $location_code)->first();
                // return response()->json(['location' => $location, 'category' => $category]);

                if (!$location) {
                    $pros = \App\Professional::all();

                    if (!$pros || $pros->count() == 0) {
                        return response()->json(['total_pages' => 0, 'companies' => [], 'total_results' => 0]);
                    }

                    // get only active users
                    $pros = $pros->filter(function ($item) {
                        if ($item->user->status == 1) {
                            return $item;
                        }
                    });

                    $pros = $pros->filter(function ($item) {
                        if ($item->user->badge) {
                            if ($item->user->badge->verified == 1) {
                                return $item;
                            }

                        }
                    });

                    $companies = $pros->filter(function ($item) use ($category) {
                        if ($item->categories && $item->categories->contains($category)) {
                            return $item;
                        }
                    });

                    $companies = $companies->map(function ($item) {
                        $item->user;
                        $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                        $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                        $item->user->badge;
                        $item->user->company;
                        $item->user->company->card;
                        $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                        $item->user->categories = $item->user->getFirst3Categories();
                        $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                        $item->user->company->location;
                        // $item->user->public_profile;
                        $item->user->user_name_profile;
                        return $item;
                    });

                    $total_results = $companies->count();

                    if ($companies->count() > 0) {
                        $companies = $companies->forPage($page, $perPage)->all();
                    } else {
                        $companies = [];
                    }

                    $total_pages = ceil($total_results / $perPage);
                    if ($page > $total_pages) {
                        $page = 1;
                    }

                    return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
                } else {

                    // get companies from location
                    $pros = \App\Professional::all();

                    if (!$pros || $pros->count() == 0) {
                        return response()->json(['total_pages' => 0, 'companies' => [], 'total_results' => 0]);
                    }

                    // get only active users
                    $pros = $pros->filter(function ($item) {
                        if ($item->user->status == 1) {
                            return $item;
                        }
                    });

                    $pros = $pros->filter(function ($item) {
                        if ($item->user->badge) {
                            if ($item->user->badge->verified == 1) {
                                return $item;
                            }

                        }
                    });

                    $companies = $pros->filter(function ($item) use ($location) {
                        if ($item->user->judets->contains($location)) {
                            return $item;
                        }
                    });

                    $companies = $companies->filter(function ($item) use ($category) {
                        if ($item->categories && $item->categories->contains($category)) {
                            return $item;
                        }
                    });

                    $companies = $companies->map(function ($item) {
                        $item->user;
                        $item->user->has_profile_photo = $item->user->hasProfilePhoto();
                        $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
                        $item->user->badge;
                        $item->user->company;
                        $item->user->company->card;
                        $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
                        $item->user->categories = $item->user->getFirst3Categories();
                        $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
                        $item->user->company->location;
                        // $item->user->public_profile;
                        $item->user->user_name_profile;

                        return $item;
                    });

                    $total_results = $companies->count();

                    if ($companies->count() > 0) {
                        $companies = $companies->forPage($page, $perPage)->all();
                    } else {
                        $companies = [];
                    }

                    $total_pages = ceil($total_results / $perPage);
                    if ($page > $total_pages) {
                        $page = 1;
                    }

                    return response()->json(['total_pages' => $total_pages, 'companies' => $companies, 'total_results' => $total_results]);
                }

            }
        }

    }

    public function getCompaniesByCategory($category_slug, $page = 1)
    {
        $perPage = 10;
        $category = \App\Category::where('slug', $category_slug)->first();

        if (!$category) {
            return response()->json(['error' => true]);
        }

        $just_category = $category;

        $pros = \App\Professional::all();

        if (!$pros) {
            return response()->json(['companies' => [], 'category' => $just_category]);
        }

        // get only active users
        $pros = $pros->filter(function ($item) {
            if ($item->user->status == 1) {
                return $item;
            }
        });

        $companies = $pros->filter(function ($item) use ($category) {
            if ($item->categories->contains($category)) {
                return $item;
            }
        });

        $companies = $companies->map(function ($item) {
            $item->user;
            $item->user->has_profile_photo = $item->user->hasProfilePhoto();
            $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
            $item->user->badge;
            $item->user->company;
            $item->user->company->card;
            $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
            $item->user->categories = $item->user->getFirst3Categories();
            $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
            $item->user->company->location;
            $item->user->judets;
            $item->user->user_name_profile;
            return $item;
        });

        if (!$companies) {
            return response()->json(['companies' => [], 'category' => $just_category]);
        }

        if ($companies->count() < 1) {
            return response()->json(['companies' => [], 'category' => $just_category]);
        }

        $total_pages = ceil($companies->count() / $perPage);
        if ($page > $total_pages) {
            $page = 1;
        }

        return response()->json(['total_pages' => $total_pages, 'companies' => $companies->forPage($page, $perPage)->all(), 'category' => $just_category]);
    }

    public function getCompaniesByCategoryAndLocation($category_slug, $location_slug, $page = 1)
    {
        $category = \App\Category::where('slug', $category_slug)->first();
        $perPage = 10;

        if (!$category) {
            return response()->json(['error' => true]);
        }

        $just_category = $category;

        $judet = \App\Judet::where('slug', $location_slug)->first();

        if (!$judet) {
            return response()->json(['error' => true]);
        }

        $just_judet = $judet;

        $pros = \App\Professional::all();

        if (!$pros) {
            return response()->json(['companies' => [], 'category' => $just_category, 'location' => $just_judet]);
        }

        // get only active users
        $pros = $pros->filter(function ($item) {
            if ($item->user->status == 1) {
                return $item;
            }
        });

        $companies = $pros->filter(function ($item) use ($category) {
            if ($item->categories->contains($category)) {
                return $item;
            }
        });

        $companies = $companies->filter(function ($item) use ($judet) {
            if ($item->user->judets->contains($judet)) {
                return $item;
            }
        });

        $companies = $companies->map(function ($item) {
            $item->user;
            $item->user->has_profile_photo = $item->user->hasProfilePhoto();
            $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
            $item->user->badge;
            $item->user->company;
            $item->user->company->card;
            $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;

            $item->user->categories = $item->user->getFirst3Categories();
            $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
            $item->user->company->location;
            $item->user->judets;
            $item->user->user_name_profile;
            return $item;
        });

        if (!$companies) {
            return response()->json(['companies' => [], 'category' => $just_category, 'location' => $just_judet]);
        }

        if ($companies->count() < 1) {
            return response()->json(['companies' => [], 'category' => $just_category, 'location' => $just_judet]);
        }

        $total_pages = ceil($companies->count() / $perPage);
        if ($page > $total_pages) {
            $page = 1;
        }

        return response()->json(['total_pages' => $total_pages, 'companies' => $companies->forPage($page, $perPage)->all(), 'category' => $just_category, 'location' => $just_judet]);
    }

    public function getVerifiedCompaniesByCategoryAndLocation($category_slug, $location_slug, $page = 1)
    {
        $category = \App\Category::where('slug', $category_slug)->first();
        $perPage = 10;

        if (!$category) {
            return response()->json(['error' => true]);
        }

        $just_category = $category;

        $judet = \App\Judet::where('slug', $location_slug)->first();

        if (!$judet) {
            return response()->json(['error' => true]);
        }

        $just_judet = $judet;

        $pros = \App\Professional::all();

        if (!$pros) {
            return response()->json(['companies' => [], 'category' => $just_category, 'location' => $just_judet]);
        }

        // get only active users
        $pros = $pros->filter(function ($item) {
            if ($item->user->status == 1) {
                return $item;
            }
        });

        // verified company
        $pros = $pros->filter(function ($item) {
            if ($item->user->badge) {
                if ($item->user->badge->verified == 1) {
                    return $item;
                }

            }
        });

        $companies = $pros->filter(function ($item) use ($category) {
            if ($item->categories->contains($category)) {
                return $item;
            }
        });

        $companies = $companies->filter(function ($item) use ($judet) {
            if ($item->user->judets->contains($judet)) {
                return $item;
            }
        });

        $companies = $companies->map(function ($item) {
            $item->user;
            $item->user->has_profile_photo = $item->user->hasProfilePhoto();
            $item->user->profile_photo = $item->user->getFullThumbnailProfilePhoto();
            $item->user->badge;
            $item->user->company;
            $item->user->company->card;
            $item->user['card_photo_exists'] = $item->user->company->card ? $item->user->company->card->photo_exists() : false;
            $item->user->categories = $item->user->getFirst3Categories();
            $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);
            $item->user->company->location;
            $item->user->judets;
            $item->user->user_name_profile;
            return $item;
        });

        if (!$companies) {
            return response()->json(['companies' => [], 'category' => $just_category, 'location' => $just_judet]);
        }

        if ($companies->count() < 1) {
            return response()->json(['companies' => [], 'category' => $just_category, 'location' => $just_judet]);
        }

        $total_pages = ceil($companies->count() / $perPage);
        if ($page > $total_pages) {
            $page = 1;
        }

        return response()->json(['total_pages' => $total_pages, 'companies' => $companies->forPage($page, $perPage)->all(), 'category' => $just_category, 'location' => $just_judet]);
    }

    public function getTopCompanies()
    {
        $users = \App\User::where('status', 1)->has('Professional')->get();

        // get only active users
        $users = $users->filter(function ($item) {
            if ($item->status == 1) {
                return $item;
            }
        });

        // get only verified companies
        $users = $users->filter(function ($item) {
            if ($item->badge) {
                if ($item->badge->verified == 1) {
                    return $item;
                }
            }
        });

        $users = $users->take(9);

        $users = $users->map(function ($item) {
            $item->has_profile_photo = $item->hasProfilePhoto();
            $item->profile_photo = $item->getFullThumbnailProfilePhoto();
            $item->badge;
            $item->company;
            $item->company->card;
            $item['card_photo_exists'] = $item->company->card ? $item->company->card->photo_exists() : false;
            $item->categories = $item->getFirst3Categories();
            $item->makeHidden(['address', 'city', 'administrative', 'phone', 'register_number', 'cui', 'user_id', 'workers', 'year_made']);
            $item->company->location;
            // $item->public_profile;
            $item->user_name_profile;
            return $item;
        });

        $users->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at', 'public_profile']);

        return response()->json(['users' => $users, 'total' => $users->count()]);
    }

    public function getCompanyByUsername($username)
    {

        $user_name = \App\Username::where('username', $username)->first();

        if ($user_name) {
            $user = $user_name->user;
        } else {
            $user = \App\User::where('username', $username)->first();
        }

        if (!$user) {
            return response()->json(['errors' => true]);
        }

        if (!$user->isCompany()) {
            return response()->json(['errors' => true]);
        }

        $user->has_profile_photo = $user->hasProfilePhoto();
        $user->profile_photo = $user->getFullThumbnailProfilePhoto();
        $user->badge;
        $user->company;
        $user->company->questions;
        $user->categories = $user->getAssocCategories();
        $user->first_categories = $user->getFirst3Categories();
        $user->makeHidden(['address', 'city', 'administrative', 'phone', 'user_id', 'workers', 'year_made']);
        $user->company->location;
        $user->public_profile;
        $user->user_name_profile;
        $user->projects = $user->projects->map(function ($item) {
            $item->categories;
            $item['first_photo'] = $item->getFirstPhoto();
            return $item;
        });
        $user->judets;
        $user->social_profiles;

        $user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'password', 'stripe_id', 'email', 'updated_at']);

        if ($user->status == 0) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['company' => $user]);
    }

    public function getInactive()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $users = \App\User::where('status', 0)->has('Professional')->get();

        $users = $users->filter(function ($item) use ($user) {
            if ($item->id !== $user->id) {
                return $item;
            }
        });

        $users->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);

        return response()->json(['users' => $users, 'total' => $users->count()]);
    }

    public function acceptCompany(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            // return response()->json(['errors' => $validator->errors()]);
            return response()->json(['errors' => true]);
        }

        $validated_fields = $validator->valid();

        $user = \App\User::find($validated_fields['id']);

        $user->status = 1;
        $user->save();

        if ($user->registration) {
            $user->registration->delete();
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

        // notify user on email and db
        Notification::send($user, new UserRegistrationDecision('accept'));

        return response()->json(['success' => true]);
    }

    public function refuseCompany(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            // return response()->json(['errors' => $validator->errors()]);
            return response()->json(['errors' => true]);
        }

        $validated_fields = $validator->valid();

        $user = \App\User::find($validated_fields['id']);

        if ($user->status != 1) {
            $user->status = 0;
        }

        $user->save();

        $registration = $user->registration ? $user->registration : new \App\Registration;
        $registration->status = 2;
        $registration->message = $validated_fields['message'] ? $validated_fields['message'] : null;
        $registration->save();

        if (!$user->badge) {
            Badge::create([
                'user_id' => $user->id,
                'verified' => false,
            ]);
        } else {
            if ($user->badge->verified) {
                $user->badge->update(['verified' => false]);
            }
        }

        // notify user on email and db
        Notification::send($user, new UserRegistrationDecision('refuse'));

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {

        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        // return response()->json(['res' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'company_type' => ['required', Rule::in(['PFA', 'II', 'IF', 'SRL', 'SRL-D', 'SNC', 'SA', 'SCS', 'SCA', 'SE'])],
            'company_location' => 'required',
            'company_name' => 'required|min:2|max:255',
            // 'year_made' => 'required|string',
            'phone' => 'required|string',
            // 'workers' => 'required|numeric|min:0',
            'cui' => 'required',
            'register_number' => 'required',
            // 'administrative' => 'required',
            // 'city' => 'required',
            // 'address' => 'required',
            // 'website' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_fields = $validator->validated();

        // 1. create the user - begin transaction

        $password_initial = substr(Str::uuid(), 0, 10); // generate random uuid and take the first 10 characters.

        DB::beginTransaction();
        if (!$user = \App\User::create([
            'first_name' => $validated_fields['first_name'],
            'last_name' => $validated_fields['last_name'],
            'email' => $validated_fields['email'],
            'password' => Hash::make($password_initial),
            'username' => $this->generateUsername($validated_fields['first_name'], $validated_fields['last_name']),
            'status' => 1,
        ])) {
            DB::rollback();
            return response()->json(['errors' => true]);
        }

        // 2. create company profile
        if ($user->isCompany()) {
            $company = $user->company;
        } else {
            $company = new \App\Company;
        }

        $company->name = $validated_fields['company_name'];
        // $company->year_made = $validated_fields['year_made'];
        $company->phone = $validated_fields['phone'];
        // $company->workers = $validated_fields['workers'];
        $company->cui = $validated_fields['cui'];
        $company->register_number = $validated_fields['register_number'];
        $company->company_type = $validated_fields['company_type'];
        // $company->administrative = $validated_fields['administrative'];
        // $company->city = $validated_fields['city'];
        // $company->address = $validated_fields['address'];
        $company->user_id = $user->id;
        // if ($request->website) {
        //     $company->website = $validated_fields['website'];
        // }

        if (!$company->save()) {
            DB::rollBack();
            return response()->json(['errors' => 'Am intampinat erori. Va rugam incercati mai tarziu.']);
        }

        // location
        if ($company->location) {
            $company->location->value = $validated_fields['company_location']['value'];
            $company->location->lat = $validated_fields['company_location']['lat'];
            $company->location->lng = $validated_fields['company_location']['lng'];
            $company->location->details = json_encode($validated_fields['company_location']['complete']);
            $company->location->save();
        } else {
            $location = new \App\CompanyLocation;
            $location->company_id = $company->id;
            $location->value = $validated_fields['company_location']['value'];
            $location->lat = $validated_fields['company_location']['lat'];
            $location->lng = $validated_fields['company_location']['lng'];
            $location->details = json_encode($validated_fields['company_location']['complete']);
            $location->save();
        }

        if (!Professional::create([
            'user_id' => $user->id,
        ])) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        // Notification Settings
        if (!$user->global_notification_settings) {
            if (!NotificationSettings::create([
                'user_id' => $user->id,
                'daily_reminder' => 1,
                'each_demand' => 1,
                'promotion' => 1,
            ])) {
                DB::rollBack();
                return response()->json(['errors' => true]);
            }
        }

        // Creare profil Credit
        if (!$user->credit) {
            if (!Credit::create([
                'user_id' => $user->id,
                'amount' => 0,
            ])) {
                DB::rollBack();
                return response()->json(['errors' => true]);
            }
        }

        if (!$user->badge) {
            if (!Badge::create([
                'user_id' => $user->id,
            ])) {
                DB::rollBack();
                return response()->json(['errors' => true]);
            }
        }

        // Adauga rol de professional
        $role = Role::where('name', 'professional')->first();
        if (!$user->syncRoles($role)) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();

        Notification::send($user, new AdminProCreatedNotification($user->email, $password_initial));

        return response()->json(['success' => 'Modificarile au fost efectuate cu succes.']);
    }

    private function generateUsername($firstname, $lastname)
    {
        $last_name = Str::slug($lastname, '-');
        $first_name = Str::slug($firstname, '-');
        $username = $last_name . '-' . $first_name . '-' . time();

        while (\App\User::where('username', $username)->get()->count() > 0) {
            // regenereaza cu un alt timestamp
            $username = $last_name . '-' . $first_name . '-' . time();
        }

        return $username;
    }

    public function verifyCompany($user_id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $user = \App\User::find($user_id);

        if (!$user) {
            return response()->json(['errors' => true]);
        }

        if (!$user->badge) {
            $badge = Badge::create([
                'user_id' => $user->id,
                'verified' => true,
            ]);
        } else {
            if (!$user->badge->verified) {
                $user->badge->update(['verified' => true]);
            }
            $badge = $user->badge;
        }

        Notification::send($user, new MarkCompanyAsVerified($user));

        return response()->json(['success' => 'Modificarile au fost efectuate cu succes.', 'badge' => $badge]);
    }

    public function unverifyCompany($user_id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $user = \App\User::find($user_id);

        if (!$user) {
            return response()->json(['errors' => true]);
        }

        if (!$user->badge) {
            $badge = Badge::create([
                'user_id' => $user->id,
                'verified' => false,
            ]);
        } else {
            if ($user->badge->verified) {
                $user->badge->update(['verified' => false]);
            }
            $badge = $user->badge;
        }

        Notification::send($user, new MarkCompanyAsUnverified($user));

        return response()->json(['success' => 'Modificarile au fost efectuate cu succes.', 'badge' => $badge]);
    }

}
