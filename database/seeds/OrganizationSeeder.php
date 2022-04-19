<?php

use App\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::create(['code' => 'PFA', 'name' => 'Persoana fizica autorizata']);
        Organization::create(['code' => 'II', 'name' => 'Intreprindere individuala']);
        Organization::create(['code' => 'IF', 'name' => 'Intreprindere familiala']);
        Organization::create(['code' => 'SRL', 'name' => 'Societate cu raspundere limitata']);
        Organization::create(['code' => 'SNC', 'name' => 'Societate in nume colectiv']);
        Organization::create(['code' => 'SA', 'name' => 'Societate pe actiuni']);
        Organization::create(['code' => 'SCS', 'name' => 'Societate in comandita simpla']);
        Organization::create(['code' => 'SCA', 'name' => 'Societate in comandita pe actiuni']);
        Organization::create(['code' => 'SE', 'name' => 'Societate europeana']);
    }
}
