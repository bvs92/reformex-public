<?php

use App\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Period::create(['days' => 1, 'price' => 5, 'visible' => false]);
        Period::create(['days' => 7, 'price' => 30, 'visible' => true]);
        Period::create(['days' => 14, 'price' => 50, 'visible' => true]);
        Period::create(['days' => 30, 'price' => 90, 'visible' => true]);
    }
}
