<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Category;

class DummyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->semesters();
    }

    private function semesters()
    {
        $data = [];

        foreach (range(1, 5) as $i) {
            $label = 'Dummy Semester ' . $i;

            $data[] = [
                'label' => $label,
                'name' => Str::slug($label),
                'group_by' => 'semesters',
                'created_at' => now(),
            ];
        }

        Category::insert($data);
    }
}
