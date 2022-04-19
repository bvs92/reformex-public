<?php

namespace App;

use App\User;
use App\CompanyCard;
use App\Organization;
use App\CompanyLocation;
use App\CompanyQuestion;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['user_id', 'name', 'cui', 'register_number', 'workers', 'year_made', 'address', 'city', 'administrative', 'phone', 'company_type'];

    // relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function location()
    {
        return $this->hasOne(CompanyLocation::class);
    }

    public function questions()
    {
        return $this->hasMany(CompanyQuestion::class);
    }

    public function card()
    {
        return $this->hasOne(CompanyCard::class);
    }

    // helpers
    public function isCompleted()
    {
        $fields = [
            'name' => $this->name,
            'cui' => $this->cui,
            'register_number' => $this->register_number,
            'phone' => $this->phone,
            'company_type' => $this->company_type,
            'location' => $this->location,
        ];

        // $ratio = 100 / count($fields);

        // $ratio_full = 100;

        $uncompleted = [];

        foreach ($fields as $item) {
            if ($item == null || trim($item) == '') {
                array_push($uncompleted, $item);
            }
        }

        if (count($uncompleted) > 0) {
            return false;
        }

        return true;
    }

    public function getCompanyTypeName()
    {

        if ($this->company_type == null || trim($this->company_type) == '') {
            return '';
        }

        if ($this->company_type == 'SRL') {
            return 'Societate cu Raspundere Limitata';
        } elseif ($this->company_type == 'PFA') {
            return 'Persoana Fizica Autorizata';
        } elseif ($this->company_type == 'II') {
            return 'Intreprindere Individuala';
        } elseif ($this->company_type == 'IF') {
            return 'Intreprindere Familiala';
        } elseif ($this->company_type == 'SRL-D') {
            return 'Societate cu Raspundere Limitata - Debutant';
        } elseif ($this->company_type == 'SNC') {
            return 'Societate in Nume Colectiv';
        } elseif ($this->company_type == 'SA') {
            return 'Societate pe Actiuni';
        } elseif ($this->company_type == 'SCS') {
            return 'Societate in Comandita Simpla';
        } elseif ($this->company_type == 'SCA') {
            return 'Societate in Comandita pe Actiuni';
        } elseif ($this->company_type == 'SE') {
            return 'Societate Europeana';
        }

    }
}
