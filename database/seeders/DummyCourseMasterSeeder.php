<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

use App\Models\CourseMaster;

class DummyCourseMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\CourseMaster::factory()->count(20)->create();

        $csvPath = storage::disk('database')->path('csv/course-masters.csv');
        dump($csvPath);

        if (!file_exists($csvPath)) return false;

        $csv = new \ParseCsv\Csv();
        $csv->auto($csvPath);

        $people = $csv->data;

        foreach ($people as $data) {
            CourseMaster::updateOrCreate(
                ['id' => $data['ID']],
                [
                    'ref_no' => $data['REF_NO'],
                    'name' => $data['NAME'],
                ]
            );
        }
    }
}
