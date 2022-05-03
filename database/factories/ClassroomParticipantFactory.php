<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\ClassroomParticipant;
use App\Models\Classroom;
use App\Models\Person;

class ClassroomParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $classroom = Classroom::inRandomOrder()->first();
        $person = Person::inRandomOrder()->first();

        $personExist = ClassroomParticipant::where('classroom_id', $classroom->id)
                                            ->where('person_id', $person->id)->first();

        while ($personExist) {
            $person = Person::inRandomOrder()->first();

            $personExist = ClassroomParticipant::where('classroom_id', $classroom->id)
                                                ->where('person_id', $person->id)->first();
        }

        return [
            'course_id' => $classroom->course_id,
            'classroom_id' => $classroom->id,
            'person_id' => $person->id,
            'created_at' => now(),
        ];
    }
}
