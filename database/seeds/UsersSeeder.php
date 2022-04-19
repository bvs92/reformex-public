<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create(['first_name' => 'Valentin',
            'last_name' => 'Biciu',
            'email' => 'valibiciu@gmail.com',
            'username' => 'valibiciu',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => Hash::make('1920131bvs'),
            'status' => '1',
        ]);

        $user->assignRole('admin');

        $user2 = User::create(['first_name' => 'Adina',
            'last_name' => 'Veselin',
            'email' => 'veselinadina@gmail.com',
            'username' => 'veselinadina',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => Hash::make('veselin.,adina.,'),
            'status' => '1',
        ]);

        $user2->assignRole('admin');
    }
}
