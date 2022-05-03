<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\CourseMaster;
use App\Models\Category;

class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $courseMaster = CourseMaster::inRandomOrder()->first();
        $semester = Category::where('group_by', 'semesters')->inRandomOrder()->first();

        return [
            'course_master_id' => $courseMaster->id,
            'semester_id' => $semester->id,
            'ref_no' => $courseMaster->ref_no,
            'name' => $courseMaster->name,
        ];
    }
}
