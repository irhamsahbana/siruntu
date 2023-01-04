<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Storage;

use App\Models\Person;

class DummyPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Person::factory()->count(20)->create();

        $csvPath = storage::disk('database')->path('csv/people.csv');
        dump($csvPath);

        if (!file_exists($csvPath)) return false;

        $csv = new \ParseCsv\Csv();
        $csv->auto($csvPath);

        $people = $csv->data;

       foreach($people as $data) {
           Person::updateOrCreate(
               ['id' => $data['ID']],
               [
                   'category_id' => $data['CATEGORY_ID'],
                   'ref_no' => $data['REF_NO'],
                   'name' => $data['NAME'],
                   'email' => $data['EMAIL'],
               ]
           );
       }
    }
}
