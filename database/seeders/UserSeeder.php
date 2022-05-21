<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

use App\Models\{
    Category,
    User,
    Meta,
};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFilePath = Storage::disk('database')->path('csv/categories.csv');

        $csv = new \ParseCsv\Csv();
        $csv->auto($csvFilePath);

        $categories =  $csv->data;
        $permissions = (collect($categories))->where('GROUP_BY', 'permissions');

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@admin';
        $user->username = 'admin';
        $user->password = bcrypt('admin');
        $user->save();

        $adminPermissionGroup = Category::where([
            'name' => 'administrator',
            'group_by' => 'permission_groups',
        ])->first();

        // generate full access for administrator groups
        foreach ($permissions as $permission) {
            $meta = new Meta();
            $meta->fk_id = $adminPermissionGroup->id;
            $meta->table_name = $adminPermissionGroup->getTable();
            $meta->key = 'permission_id';
            $meta->value = $permission['ID'];
            $meta->save();
        }

        // create admin user his permission group
        Meta::create([
            'fk_id' => $user->id,
            'table_name' => $user->getTable(),
            'key' => 'permission_group_id',
            'value' => $adminPermissionGroup->id,
        ]);
    }
}
