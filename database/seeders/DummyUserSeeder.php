<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Category;
use App\Models\Person;
use App\Models\User;
use App\Models\Meta;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $people = $this->getPeople();

        $personCategories = Category::where('group_by', 'people')->get();
        $personLecturer = $personCategories->where('name', 'lecturer')->first();
        $personLearner = $personCategories->where('name', 'learner')->first();

        foreach ($people as $person) {
            $user = $this->createUser($person);

            // get permission group
            $permissionGroups = Category::where('group_by', 'permission_groups')->get();
            $lecturerGroup = $permissionGroups->where('name', 'lecturer')->first();
            $learnerGroup = $permissionGroups->where('name', 'learner')->first();

            // select permission group by person category
            $value = null;
            switch($person->category_id) {
                case $personLecturer->id: // if lecturer
                    $value = $lecturerGroup->id;
                    break;
                case $personLearner->id: // if learner
                    $value = $learnerGroup->id;
                    break;
            }

            // create permission group for user if he/she is lecturer or learner
            if ($value)
                Meta::create([
                    'fk_id' => $user->id,
                    'table_name' => $user->getTable(),
                    'key' => 'permission_group_id',
                    'value' => $value,
                ]);
        }
    }

    private function getPeople()
    {
        return Person::select(
            'people.id',
            'people.category_id',
            'people.name',
            'people.email',

            'categories.name AS category_name',
            'categories.group_by',
        )
        ->join('categories', 'categories.id', '=', 'people.category_id')
        ->get();
    }

    private function createUser(Person $person)
    {
        try {
            $user = new User();
            $name = explode(' ', $person->name);

            $user->username = strtolower($name[1]);
            $user->person_id = $person->id;
            $user->name = $person->name;
            $user->email = $person->email;
            $user->password = bcrypt('password');
            $user->save();
        } catch (\Throwable $th) {
            $user = new User();
            $name = explode(' ', $person->name);

            $user->username = strtolower($name[1]) . '_' . $person->id;
            $user->person_id = $person->id;
            $user->name = $person->name;
            $user->email = $person->email;
            $user->password = bcrypt('password');
            $user->save();
        }

        return $user;
    }
}
