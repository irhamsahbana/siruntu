<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Category;
use App\Models\Course;


class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $mode = Category::where('group_by', 'classroom_modes')->where('name', 'normal')->first();
        $course = Course::inRandomOrder()->first();
        $classes = ['Kelas A', 'Kelas B', 'Kelas C', 'Kelas D', 'Kelas E', 'Kelas F'];

        return [
            'course_id' => $course->id,
            'mode_id' => $mode->id,
            'name' => $this->faker->randomElement($classes),
            'created_at' => now(),
        ];
    }
}
