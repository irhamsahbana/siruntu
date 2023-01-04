<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Storage;

use App\Models\Person;

class DBtoCSVPeople extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFilePath = storage::disk('database')->path('csv/people.csv');
        $csv = new \ParseCsv\Csv();

        if (!file_exists($csvFilePath)) {
            touch($csvFilePath);
            $csv->save($csvFilePath, [['ID', 'CATEGORY_ID', 'REF_NO', 'NAME', 'EMAIL']]);
        }

        $data = Person::orderBy('id', 'asc')
            ->get()
            ->toArray();

        $toCSV = [];
        foreach ($data as $row) {
            $toCSV[] = [
                $row['id'],
                $row['category_id'],
                $row['ref_no'],
                $row['name'],
                $row['email'],
            ];
        }

        $csv->save($csvFilePath, $toCSV, true);
    }
}
