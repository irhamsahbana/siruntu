<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

use App\Models\{
    Category,
    Meta,
};

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories =  $this->loadFile();
        $permissions = (collect($categories))->where('GROUP_BY', 'permissions');

        $lecturerGroupPermissions = $permissions->whereIn('NAME', [
            'course-read',
            'classroom-read'
        ]);

        $learnerGroupPermissions = $permissions->whereIn('NAME', [
            'course-read',
            'classroom-read'
        ]);

        $permissionGroups = Category::where('group_by', 'permission_groups')->get();

        $lecturerGroup = $permissionGroups->where('name', 'lecturer')->first();
        $learnerGroup = $permissionGroups->where('name', 'learner')->first();


        $this->generatePermissionsForGroup($lecturerGroup, $lecturerGroupPermissions);
        $this->generatePermissionsForGroup($learnerGroup, $learnerGroupPermissions);

    }

    private function loadFile()
    {
        $csvFilePath = Storage::disk('database')->path('csv/categories.csv');

        $csv = new \ParseCsv\Csv();
        $csv->auto($csvFilePath);

        return $csv->data;
    }

    private function generatePermissionsForGroup(Category $group, Collection $permissions)
    {
        foreach ($permissions as $permission) {
            $meta = new Meta();
            $meta->fk_id = $group->id;
            $meta->table_name = $group->getTable();
            $meta->key = 'permission_id';
            $meta->value = $permission['ID'];
            $meta->save();
        }
    }
}
