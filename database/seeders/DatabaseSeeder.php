<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategorySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionGroupSeeder::class);

        $this->DummySeeder();
    }

    private function DummySeeder()
    {
        $this->call(DummyCategorySeeder::class);
        $this->call(DummyCourseMasterSeeder::class);
        $this->call(DummyCourseSeeder::class);
        $this->call(DummyClassroomSeeder::class);
        $this->call(DummyPersonSeeder::class);
        $this->call(DummyUserSeeder::class);

        $this->call(DummyClassroomParticipantSeeder::class);
    }
}
