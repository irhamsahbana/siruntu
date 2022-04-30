<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DummyCourseMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\CourseMaster::factory()->count(20)->create();
    }
}
