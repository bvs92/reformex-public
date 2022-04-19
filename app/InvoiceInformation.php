<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class InvoiceInformation extends Model
{
    protected $fillable = ['user_id', 'type', 'company_type', 'first_name', 'last_name', 'company_name', 'phone', 'address', 'cui', 'number'];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
