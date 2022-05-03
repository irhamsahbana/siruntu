<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Category;

class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = Category::where('group_by', 'people')
                            ->whereIn('name', ['lecturer', 'learner'])
                            ->inRandomOrder()
                            ->first();

        return [
            'category_id' => $category->id,
            'ref_no' => $this->faker->unique()->randomNumber(6),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'created_at' => now(),
        ];
    }
}
