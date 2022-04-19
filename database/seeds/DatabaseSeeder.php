<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            CategorySeeder::class,
            ProjectCategorySeeder::class,
            UsersSeeder::class,
            OrganizationSeeder::class,
            JudeteSeeder::class,
            JudetSlugSeeder::class,
            PeriodSeeder::class,
            // JudeteSlugSeeder::class,
        ]);
    }
}
