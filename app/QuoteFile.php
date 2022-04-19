<?php

namespace App;

use App\Quote;
use App\User;
use Illuminate\Database\Eloquent\Model;

class QuoteFile extends Model
{
    protected $fillable = ['quote_id', 'user_id', 'name', 'extension', 'path', 'mime_type'];

    // Relationships

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
