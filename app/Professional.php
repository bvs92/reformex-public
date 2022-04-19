<?php

namespace App;

use App\Category;
use App\Demand;
use App\Quote;
use App\Review;
use App\Timeline;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $fillable = ['user_id', 'range', 'city', 'administrative', 'postal_code', 'lat', 'lng'];

    // Relationships

    public function demands()
    {
        return $this->belongsToMany(Demand::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helpers
    public function isProCompleted()
    {
        if (!$this->lat || !$this->lng || !$this->range || !$this->city) {
            return false;
        }

        if (!$this->categories) {
            return false;
        }

        if ($this->categories->count() < 1) {
            return false;
        }

        return true;
    }

    // Getters

    // public function getName()
    // {
    //     if($this->name)
    //         return $this->name;
    //     else
    //         return 'Nesetat';
    // }

    public function hasDemand(Demand $demand)
    {
        $result = $this->demands()->find($demand);
        if ($result->count() >= 1) {
            return true;
        } else {
            return false;
        }

    }

    public function getName()
    {
        return $this->user->company->name ?? $this->user->getName();
    }

    public function getLocation()
    {
        return $this->city . ", " . $this->administrative;
    }

    public function getRange()
    {
        return $this->range;
    }

    public function hasQuoteOn(Demand $demand)
    {
        return $this->quotes()->where('demand_id', $demand->id)->count();
    }

    public function getTotal()
    {
        return $this->count();
    }

    public function getRating()
    {
        return $this->reviews()->sum('rating') / $this->reviews->count();
    }

    public function getPersonalizedDemands()
    {
        // Log::info('PRO ID is: ' . $this->id);
        // Log::info('Categories are: ' . $this->categories);

        if ($this->lat == null || $this->lng == null) {
            return collect();
        }

        $result = Demand::search('')->with([
            'aroundLatLng' => $this->lat . ',' . $this->lng,
            'aroundRadius' => $this->range ?? 1,
            'aroundPrecision' => 2000,
            'getRankingInfo' => true,
            // 'filters' => "categories:" . $filter
        ])->get();

        // Log::info("Jos sunt cererile.");
        // Log::info($result);

        $pro = $this;
        //Check if any demand is bought
        $result = $result->filter(function ($item) use ($pro) {
            // Log::info("PRO ID AICI ESTE: " . $pro->id);
            if (!$item->isBought($pro->id)) {
                return $item;
            }
        });

        // check if demand belongs to me MAKE IT WORK
        $result = $result->filter(function ($item) use ($pro) {
            if ($item->belongsToUser($pro->user) != true) {
                return $item;
            }
        });

        // get only demands that are in the selected categories.
        $categories = $pro->categories;

        $personalResult = $result->filter(function ($item) use ($categories) {
            // dd($categories);
            foreach ($item->categories as $cat) {
                if ($categories->contains($cat->id)) {
                    return $item;
                }
            }

            // dd($item->categories);
        });

        return $personalResult;
    }

}
