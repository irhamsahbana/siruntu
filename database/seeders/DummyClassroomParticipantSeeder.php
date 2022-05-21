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
        // \App\Models\ClassroomParticipant::factory()->count(200)->create();

        $classrooms = \App\Models\Classroom::all();
        $people = \App\Models\Person::all();
        $totalPeople = $people->count();

        foreach ($classrooms as $classroom) {

            $participants = $people->random(rand(1, $totalPeople));

            foreach ($participants as $participant) {
                if ($classroom->participants()->where('person_id', $participant->id)->exists())
                    continue;

                \App\Models\ClassroomParticipant::create([
                    'course_id' => $classroom->course_id,
                    'classroom_id' => $classroom->id,
                    'person_id' => $participant->id,
                ]);
            }
        }

    }
}
