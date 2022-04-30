<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DummyPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Person::factory()->count(20)->create();
    }
}
