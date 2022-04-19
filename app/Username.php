<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Username extends Model
{
    //
    protected $fillable = ['user_id', 'username'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // helpers
    public function isCompleted()
    {
        $fields = [
            'username' => $this->username,
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
}
