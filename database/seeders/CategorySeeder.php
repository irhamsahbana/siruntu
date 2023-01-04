<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

use App\Models\{
    Category,
};

class CategorySeeder extends Seeder
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
        $csv->auto($csvFilePath);

        $categories =  $csv->data;

        foreach ($categories as $category) {
            // Category::create([
            //     'id' => $category['ID'],
            //     'category_id' => $category['CATEGORY_ID'] == "" ? null : $category['CATEGORY_ID'],
            //     'name' => $category['NAME'],
            //     'group_by' => $category['GROUP_BY'],
            //     'label' => $category['LABEL'],
            //     'notes' => $category['NOTES'],

            //     'created_at' => \Carbon\Carbon::now(),
            //     'updated_at' => null,
            // ]);

            Category::updateOrCreate(
                ['id' => $category['ID']],
                [
                    'category_id' => $category['CATEGORY_ID'] == "" ? null : $category['CATEGORY_ID'],
                    'name' => $category['NAME'],
                    'group_by' => $category['GROUP_BY'],
                    'label' => $category['LABEL'],
                    'notes' => $category['NOTES'],
                ]
            );
        }
    }
}
