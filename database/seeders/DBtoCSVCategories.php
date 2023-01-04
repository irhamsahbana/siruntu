<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Storage;

use App\Models\{
    Category,
};

class DBtoCSVCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFilePath = storage::disk('database')->path('csv/categories.csv');

        $csv = new \ParseCsv\Csv();
        if (!file_exists($csvFilePath)) {
            touch($csvFilePath);
            $csv->save($csvFilePath, [['ID', 'CATEGORY_ID', 'NAME', 'LABEL', 'NOTES', 'GROUP_BY']]);
        }

        $data = Category::whereNotIn('group_by', ['provinces', 'cities'])
            ->orderBy('id', 'asc')
            ->orderBy('group_by', 'desc')
            ->get()
            ->toArray();

        $toCSV = [];
        foreach ($data as $row) {
            $toCSV[] = [
                $row['id'],
                $row['category_id'] === null ? "" : $row['category_id'],
                $row['name'],
                $row['label'],
                $row['notes'] === null ? "" : $row['notes'],
                $row['group_by'],
            ];
        }

        $csv->save($csvFilePath, $toCSV, true);
    }
}
