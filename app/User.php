<?php

namespace App;

use App\Activity;
use App\Announcement;
use App\Badge;
use App\Buyer;
use App\ClientMessage;
use App\ClientMessageFile;
use App\Company;
use App\CompanyReview;
use App\Coupon;
use App\CouponRequest;
use App\Credit;
use App\Demand;
use App\DemandReport;
use App\Invoice;
use App\InvoiceInformation;
use App\Judet;
use App\NotificationSettings;
use App\Payment;
use App\Professional;
use App\Profile;
use App\Quote;
use App\QuoteFile;
use App\RefundsDemand;
use App\Registration;
use App\ResponseTicket;
use App\Review;
use App\SocialProfile;
use App\Subscription;
use App\Ticket;
use App\TicketAction;
use App\TicketFile;
use App\Timeline;
use App\Transaction;
use App\User;
use App\Username;
use App\UserNotificationSettings;
use App\UserPublicProfile;
use App\WorkProject;
use App\WorkProjectPhoto;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'username', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function routeNotificationForNexmo($notification)
    {
        // return $this->phone;
        return '0756472072';
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Relationships
     */

    // public function role()
    // {
    //     return $this->hasOne(Role::class);
    // }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function invoice_information()
    {
        return $this->hasOne(InvoiceInformation::class);
    }

    public function global_notification_settings()
    {
        return $this->hasOne(NotificationSettings::class);
    }

    public function company_review()
    {
        return $this->hasOne(CompanyReview::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function judets()
    {
        return $this->belongsToMany(Judet::class)->withTimestamps();
    }

    public function badge()
    {
        return $this->hasOne(Badge::class);
    }

    public function public_profile()
    {
        return $this->hasOne(UserPublicProfile::class);
    }

    public function user_name_profile()
    {
        return $this->hasOne(Username::class);
    }

    public function demands_bought()
    {
        return $this->hasMany(Buyer::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function coupons_requests()
    {
        return $this->hasMany(CouponRequest::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class)->withTimestamps();
    }

    public function demands()
    {
        return $this->hasMany(Demand::class);
    }

    public function professional()
    {
        return $this->hasOne(Professional::class);
    }

    public function credit()
    {
        return $this->hasOne(Credit::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function resolvers()
    {
        return $this->hasMany(\App\TicketResolver::class);
    }

    public function response_tickets()
    {
        return $this->hasMany(ResponseTicket::class);
    }

    public function ticket_files()
    {
        return $this->hasMany(TicketFile::class);
    }

    public function quote_files()
    {
        return $this->hasMany(QuoteFile::class);
    }

    public function client_message_files()
    {
        return $this->hasMany(ClientMessageFile::class);
    }

    public function client_messages()
    {
        return $this->hasMany(ClientMessage::class);
    }

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function refundsDemand()
    {
        return $this->hasMany(RefundsDemand::class);
    }

    public function social_profiles()
    {
        return $this->hasMany(SocialProfile::class);
    }

    public function projects()
    {
        return $this->hasMany(WorkProject::class);
    }

    public function projects_photos()
    {
        return $this->hasMany(WorkProjectPhoto::class);
    }

    public function reports()
    {
        // reported demands
        return $this->hasMany(DemandReport::class);
    }

    public function ticket_actions()
    {
        return $this->hasMany(TicketAction::class);
    }

    public function notification_settings()
    {
        return $this->hasOne(UserNotificationSettings::class);
    }

    public function registration()
    {
        return $this->hasOne(Registration::class);
    }

    //Getters

    // public function hasQuoteOn(Demand $demand)
    // {
    //     return $this->quotes()->where('demand_id', $demand->id)->count();
    // }

    public function necessary()
    {
        $user = auth()->user();
        $user['is_pro'] = $user->isPro();
        $user['is_admin'] = $user->isAdmin();
        $user['complete_name'] = $user->getTheName();
        $user['profile_photo'] = $user->getFullProfilePhoto();
        $user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);

        return $user;
    }

    public function isEmailVerified()
    {
        if ($this->email_verified_at != null || $this->email_verified_at != '') {
            return true;
        }
        return false;
    }

    public function isCompletedPublicProfile()
    {

        $user = auth()->user();
        $username = $user->user_name_profile;
        $public_profile = $user->public_profile;
        $judets = $user->judets;
        $categories = $user->professional->categories;
        $social_profiles = $user->social_profiles;

        $fields = [
            'description' => $public_profile->description,
            'website' => $public_profile->website,
            'username' => $username,
            'judets' => $judets,
            'categories' => $categories,
            'social_profiles' => $social_profiles,
        ];

        $uncompleted = [];

        foreach ($fields as $item) {
            if ($item == null) {
                array_push($uncompleted, $item);
            }
        }

        if (count($uncompleted) > 0) {
            return false;
        }

        return true;
    }

    public function getName()
    {
        return ucfirst($this->last_name) . " " . ucfirst($this->first_name);
    }

    public function getTheName()
    {
        if ($this->isPro()) {
            return $this->professional->getName();
        } else {
            return ucfirst($this->first_name) . " " . ucfirst($this->last_name);
        }
    }

    public function getCompanyName()
    {

        return $this->professional->getName();

    }

    public function creationData()
    {
        return $this->created_at->diffForHumans();
    }

    public function firstSubscription()
    {
        return $this->subscriptions()->first();
    }

    public function activeSubscription()
    {
        return $this->subscriptions()->first()->name ?? 'Nedefinit';
    }

    public function getFullProfilePhoto()
    {
        if (!$this->profile) {
            return 'images/avatars/default-photo.png';
        }

        return 'storage/avatars/' . $this->profile->profile_photo;
    }

    public function getFullThumbnailProfilePhoto()
    {

        if (!$this->profile) {
            return 'images/avatars/default-photo.png';
        }

        $path = 'uploads/avatars/' . $this->profile->profile_photo;
        // return Storage::disk('do_spaces')->url($path);

        if (Storage::disk('do_spaces')->exists($path)) {
            return Storage::disk('do_spaces')->url($path);
        } else {
            return 'images/avatars/default-photo.png';
        }

        // return 'storage/avatars/thumbnails/' . $this->profile->profile_photo;
    }

    public function hasProfilePhoto()
    {
        if (!$this->profile) {
            return false;
        }

        if ($this->profile->profile_photo == 'default-photo.png') {
            return false;
        }

        return true;
    }

    public function cardPhotoExists($name_file)
    {

        $pathToFile = storage_path('app/public') . '/cards' . '/' . $name;
        if (file_exists($pathToFile)) {
            return true;
        } else {
            return false;
        }
    }

    public function isPro()
    {
        return $this->professional ? true : false;
    }

    public function isCompany()
    {
        return $this->company ? true : false;
    }

    public function getCreditAmount()
    {
        return $this->credit->amount / 100;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isActive()
    {
        return $this->status == '1' ? true : false;
    }

    public function userStatus($id)
    {
        $user = User::findOrFail($id);

        return $user->status;
    }

    // get categories
    public function getAssocCategories()
    {
        return $this->isPro() ? $this->professional->categories : null;
    }

    public function getFirst3Categories()
    {
        return $this->isPro() ? $this->professional->categories()->take(3)->get() : null;
    }

    public function getCustomer($customer_id)
    {
        return $this->where('stripe_id', $customer_id)->first();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function hasRoles()
    {
        return ($this->roles && $this->roles->count() > 0) ? true : false;
    }

    public function getFirstRole()
    {
        $role = $this->hasRoles() ? $this->roles->first() : null;
        if ($role) {
            if ($role->name == 'admin') {
                return 'Administrator';
            } else if ($role->name == 'professional') {
                return 'Profesionist';
            } else if ($role->name == 'standard') {
                return 'Standard';
            } else if ($role->name == 'moderator') {
                return 'Moderator';
            }
        } else {
            return null;
        }
    }

    public function getRating()
    {
        return $this->reviews()->sum('rating') / $this->reviews->count();
    }

    public function getSocialProfile($type)
    {
        return $this->social_profiles()->where('type', $type)->first();
    }

    public function hasSocialProfile($type)
    {
        return $this->social_profiles()->where('type', $type)->first() ? true : false;
    }

    public function existsSocialProfile($type)
    {
        if ($result = $this->social_profiles()->where('type', $type)->first()) {
            return $result->username !== null ? true : false;
        }

        return false;
    }

    public function hasUsername()
    {
        return $this->username !== null ? true : false;
    }

    public function getUsername()
    {
        return $this->hasUsername() ? $this->username : null;
    }
}
