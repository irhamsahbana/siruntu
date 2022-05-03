<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DummyClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Classroom::factory()->count(20)->create();
    }
}
