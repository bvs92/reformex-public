<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'professional']);
        Role::create(['name' => 'standard']);
        Role::create(['name' => 'editor']);
        Role::create(['name' => 'moderator']);
    }
}
