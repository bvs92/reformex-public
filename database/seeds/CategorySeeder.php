<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'General', 'slug' => 'general', 'price' => 500, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Amenajare', 'slug' => 'amenajare', 'price' => 1100, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Acoperis', 'slug' => 'acoperis', 'price' => 900, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Betoane', 'slug' => 'betoane', 'price' => 900, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Construcție', 'slug' => 'constructie', 'price' => 1200, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Demolare', 'slug' => 'demolare', 'price' => 500, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Renovare', 'slug' => 'renovare', 'price' => 1000, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Automatizare', 'slug' => 'automatizare', 'price' => 500, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Dulgherie', 'slug' => 'dulgherie', 'price' => 700, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Electricitate', 'slug' => 'electricitate', 'price' => 600, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Fațade', 'slug' => 'fatade', 'price' => 700, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Garduri', 'slug' => 'garduri', 'price' => 800, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Încălzire', 'slug' => 'incalzire', 'price' => 1200, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Izolații', 'slug' => 'izolatii', 'price' => 1000, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Pavaje', 'slug' => 'pavaje', 'price' => 900, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Pardoseli', 'slug' => 'pardoseli', 'price' => 900, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Pereți', 'slug' => 'pereti', 'price' => 400, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Pergole', 'slug' => 'pergole', 'price' => 1000, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Piscine', 'slug' => 'piscine', 'price' => 1200, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Porți', 'slug' => 'porti', 'price' => 900, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Șape', 'slug' => 'sape', 'price' => 700, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Scări', 'slug' => 'scari', 'price' => 800, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Tavane', 'slug' => 'tavane', 'price' => 300, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Tencuieli', 'slug' => 'tencuieli', 'price' => 400, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Terase', 'slug' => 'terase', 'price' => 900, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Termopane', 'slug' => 'termopane', 'price' => 900, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Ventilație', 'slug' => 'ventilatie', 'price' => 900, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Zidărie', 'slug' => 'zidarie', 'price' => 700, 'uuid' => $this->generateUUID()]);
        Category::create(['name' => 'Zugrăveli', 'slug' => 'zugraveli', 'price' => 300, 'uuid' => $this->generateUUID()]);
    }

    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while (Category::where('uuid', $id)->get()->count() > 0) {
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
