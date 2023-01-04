<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

use App\Models\CourseMaster;

class DBtoCSVCourseMasters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFilePath = storage::disk('database')->path('csv/course-masters.csv');
        $csv = new \ParseCsv\Csv();

        if (!file_exists($csvFilePath)) {
            touch($csvFilePath);
            $csv->save($csvFilePath, [['ID', 'REF_NO', 'NAME',]]);
        }

        $data = CourseMaster::orderBy('id', 'asc')
            ->get()
            ->toArray();

        $toCSV = [];
        foreach ($data as $row) {
            $toCSV[] = [
                $row['id'],
                $row['ref_no'],
                $row['name'],
            ];
        }

        $csv->save($csvFilePath, $toCSV, true);
    }
}
