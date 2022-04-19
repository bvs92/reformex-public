<?php

namespace App;

use App\User;
use App\Buyer;
use App\Quote;
use App\Demand;
use App\Detail;
use App\Review;
use App\Winner;
use App\Activity;
use App\Category;
use App\Prospect;
use App\Timeline;
use App\DemandFile;
use App\DemandReport;
use App\Professional;
use App\ClientMessage;
use App\DemandAttachment;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use Searchable;

    protected $fillable = ['user_id', 'subject', 'city', 'administrative', 'message', 'name', 'email', 'phone', 'lat', 'lng', 'state', 'status', 'uuid'];

    public function searchableAs()
    {
        return 'dev_DEMANDS';
    }

    public function toSearchableArray()
    {
        // $this->categories;

        $this->makeHidden(['email', 'phone', 'message']);

        $array = $this->toArray();
        // $array = $this->transform($array);

        $array['_geoloc'] = [
            'lat' => $array['lat'],
            'lng' => $array['lng'],
        ];

        unset($array['lat'], $array['lng']);

        // return $array;

        // Customize array...
        $extra = [
            'categories' => $this->categories->pluck('name')->toArray(),
        ];

        return array_merge($array, $extra);
        // return $array;
    }

    /**
     *  Relationships
     */

    public function buyers()
    {
        return $this->hasMany(Buyer::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function professionals()
    {
        return $this->belongsToMany(Professional::class)->withTimestamps();
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function client_messages()
    {
        return $this->hasMany(ClientMessage::class);
    }

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }

    public function detail()
    {
        return $this->hasOne(Detail::class);
    }

    public function prospects()
    {
        return $this->hasMany(Prospect::class);
    }

    public function winners()
    {
        return $this->hasMany(Winner::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function reports()
    {
        return $this->hasMany(DemandReport::class);
    }

    public function files()
    {
        return $this->hasMany(DemandFile::class);
    }

    public function attachments()
    {
        return $this->hasMany(DemandAttachment::class);
    }

    // Getters

    public function showPublishDate()
    {
        if ($this->created_at == null) {
            return 'Nicio data setata.';
        } else {
            return $this->created_at->diffForHumans() ?? 'Nesetat';
        }

    }

    public function quotesNumber()
    {
        return $this->quotes()->count();
    }

    public function hasProfessional(Professional $pro)
    {
        // $result = $this->professionals()->find($pro);
        $result = $this->professionals()->where('user_id', $pro->user->id)->first();

        if (!$result || $result->count() < 1) {
            return false;
        }

        return true;
    }

    public function hasBuyer(User $user)
    {
        $result = $this->buyers()->where('user_id', $user->id)->first();

        if (!$result || $result->count() < 1) {
            return false;
        }

        return true;
    }

    public function hasUser()
    {
        if (isset($this->user)) {
            return true;
        } else {
            return false;
        }

    }

    public function belongsToMe()
    {
        if ($this->hasUser()) {
            return $this->user->id == auth()->user()->id ? true : false;
        }
        // dd('trece');

        return false;
    }

    public function belongsToUser($user)
    {
        if ($this->hasUser()) {
            return $this->user->id == $user->id ? true : false;
        }
        // dd('trece');

        return false;
    }

    public function isBought($pro_id)
    {
        if ($this->professionals()->where('professional_id', $pro_id)->first()) {
            return true;
        }
        return false;
    }

    public function getNumberBought()
    {
        return $this->professionals->count();
    }

    public function getNumberOfBuyers()
    {
        return $this->professionals->count();
    }

    // Helpers

    // public function exists()
    // {
    //     return $this !== null ? true : false;
    // }

    public function firstCategory()
    {
        return $this->categories()->first()->name ?? "General";
    }

    public function getCalculatedPrice()
    {
        if (!isset($this->categories)) {
            return 500;
        }

        if (count($this->categories) == 1) {
            return $this->categories->first()->price;
        }

        $total = 0;
        if (count($this->categories) > 1) {
            foreach ($this->categories as $category) {
                $total += $category->price;
            }

            return $total / count($this->categories);
        }
    }

    public function getCalculatedPriceNormal()
    {
        if (!isset($this->categories)) {
            return 5;
        }

        if (count($this->categories) == 1) {
            return round($this->categories->first()->price / 100, 2);
        }

        $total = 0;
        if (count($this->categories) > 1) {
            foreach ($this->categories as $category) {
                $total += $category->price;
            }

            return round(($total / count($this->categories)) / 100, 2);
            // return ($total / count($this->categories)) / 100;
        }
    }

    public function getCost()
    {
        return $this->activities->first()->amount ?? 'Indisponibil';
    }

    public function isActive()
    {
        return $this->detail->status == 0 ? true : false;
    }

    public function isCompleted()
    {
        return $this->detail->status == 1 ? true : false;
    }

    public function isVerified() // by admin

    {
        return $this->status == '1' ? true : false;
    }

    public function isUnverified() // by admin

    {
        return $this->status == '0' ? true : false;
    }

    public function isFalse() // by admin

    {
        return $this->status == '2' ? true : false;
    }

    public function maximumOffers()
    {
        return $this->detail->offers;
        // return 1;
    }

    public function hasWinners()
    {
        return $this->winners ? true : false;
    }

    public function getAllWinners()
    {
        // status = 1; castigator acceptat de client.
        return $this->winners;
    }

    public function hasActiveWinner()
    {
        return $this->winners()->where('status', '1')->first() ? true : false;
    }

    public function getWinner()
    {
        // status = 1; castigator acceptat de client.
        return $this->winners()->where('status', '1')->first();
    }

    public function hasLosers()
    {
        return count($this->winners->where('status', '2')->get()) > 0 ? true : false;
    }

    public function getLosers()
    {
        return $this->winners->where('status', '2')->get();
    }

    public function hasReview()
    {
        return $this->review ? true : false;
    }

    public function hasProspect($pro_id)
    {
        $pro = Professional::findOrFail($pro_id);

        $prospect = $this->prospects()->where('professional_id', $pro_id)->first();

        return $prospect !== null ? true : false;
    }

    public function hasProspects()
    {
        return $this->prospects ? true : false;
    }

    public function hasUUID()
    {
        return $this->uuid ? true : false;
    }

    public function getDemandId()
    {
        return $this->hasUUID() ? $this->uuid : $this->id;
    }

    public function getDisponibleId()
    {
        return $this->hasUUID() ? $this->uuid : $this->id;
    }

    public function getState()
    {
        return $this->state;
    }

    public function isStateActive()
    {
        return $this->state == 1;
    }

    public function isStateInactive()
    {
        return $this->state == 0;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isReportedBy($user)
    {
        $reports = $this->reports()->where('user_id', $user->id)->first();
        if ($reports && $reports->count() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function getReportFor($user)
    {
        $report = $this->reports()->where('user_id', $user->id)->first();

        if (!$report) {
            return null;
        }

        return $report;
    }

}
