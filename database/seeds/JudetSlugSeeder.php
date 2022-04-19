<?php

use App\Judet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JudetSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $judets = Judet::all();
        $judets->each(function ($item) {
            $item->update(['slug' => Str::slug($item->name_simple)]);
        });

    }
}
