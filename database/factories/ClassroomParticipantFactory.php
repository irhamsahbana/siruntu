<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\ClassroomParticipant as Participant;
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
        // $classroom = Classroom::inRandomOrder()->first();
        // $person = Person::inRandomOrder()->first();

        // $personExist = Participant::where('classroom_id', $classroom->id)
        //                                     ->where('person_id', $person->id)->first();

        // while ($personExist) {
        //     $person = Person::inRandomOrder()->first();

        //     $personExist = Participant::where('classroom_id', $classroom->id)
        //                                         ->where('person_id', $person->id)->first();
        // }

        $generator = $this->generateParticipant();

        $classroom = $generator['classroom'];
        $person = $generator['person'];

        return [
            'course_id' => $classroom->course_id,
            'classroom_id' => $classroom->id,
            'person_id' => $person->id,
            'created_at' => now(),
        ];
    }

    private function generateParticipant()
    {
        $classroom = Classroom::inRandomOrder()->first();
        $person = Person::inRandomOrder()->first();

        $personExist = Participant::where('classroom_id', $classroom->id)
                                            ->where('person_id', $person->id)->first();
        if($personExist) {
            $this->generateParticipant();
        }

        while ($personExist) {
            $person = Person::inRandomOrder()->first();

            $personExist = Participant::where('classroom_id', $classroom->id)
                                                ->where('person_id', $person->id)->first();
        }

        return [
            'classroom' => $classroom,
            'person' => $person,
        ];
    }
}
