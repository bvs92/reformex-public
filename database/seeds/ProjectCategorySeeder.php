<?php

use App\ProjectCategory;
use Illuminate\Database\Seeder;

class ProjectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectCategory::create(['name' => 'General', 'slug' => 'general', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Amenajare', 'slug' => 'amenajare', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Acoperiș', 'slug' => 'acoperis', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Betoane', 'slug' => 'betoane', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Construcție', 'slug' => 'constructie', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Demolare', 'slug' => 'demolare', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Renovare', 'slug' => 'renovare', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Automatizare', 'slug' => 'automatizare', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Dulgherie', 'slug' => 'dulgherie', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Electricitate', 'slug' => 'electricitate', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Fațade', 'slug' => 'fatade', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Garduri', 'slug' => 'garduri', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Încalzire', 'slug' => 'incalzire', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Izolații', 'slug' => 'izolatii', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Pavaje', 'slug' => 'pavaje', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Pardoseli', 'slug' => 'pardoseli', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Pereți', 'slug' => 'pereti', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Pergole', 'slug' => 'pergole', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Piscine', 'slug' => 'piscine', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Porți', 'slug' => 'porti', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Șape', 'slug' => 'sape', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Scări', 'slug' => 'scari', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Tavane', 'slug' => 'tavane', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Tencuieli', 'slug' => 'tencuieli', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Terase', 'slug' => 'terase', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Termopane', 'slug' => 'termopane', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Ventilație', 'slug' => 'ventilatie', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Zidărie', 'slug' => 'zidarie', 'uuid' => $this->generateUUID()]);
        ProjectCategory::create(['name' => 'Zugrăveli', 'slug' => 'zugraveli', 'uuid' => $this->generateUUID()]);
    }

    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while (ProjectCategory::where('uuid', $id)->get()->count() > 0) {
            // regenereaza daca exista
            $res = \Illuminate\Support\Str::uuid();
            // $res = rand(0, 99);
            $id = substr($res, 0, 8);
            // echo $id . '<br/>';
        }

        // echo 'JOS: ' . $id . '<br/>';
        return $id;

        // => id este unic si poate fi atasat unei cereri.
    }
}
