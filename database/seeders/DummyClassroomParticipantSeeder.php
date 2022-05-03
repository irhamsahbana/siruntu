<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DummyClassroomParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ClassroomParticipant::factory()->count(200)->create();
    }
}
