<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseMasterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ref_no' => $this->faker->randomNumber(6),
            'name' => $this->faker->sentence(2)
        ];
    }
}
