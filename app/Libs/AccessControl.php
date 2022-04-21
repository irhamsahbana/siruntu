<?php

namespace App\Libs;

use Illuminate\Support\Facades\DB;

use App\Models\User;

class AccessControl extends AbstractAccessControl
{
    private $permissions = [];

    public function __construct(User $model)
    {
        parent::__construct($model);

        $this->permissions = $this->getPermissions();
    }

    public function getPermissionGroups()
    {
        $permissionGroups = User::select('permissions.name')
            ->join('metas as permission_groups', 'permission_groups.fk_id', '=', 'users.id') // get permission groups that attached to user
            ->join('categories as permissions', 'permissions.id', '=', 'permission_groups.value') // get permissions that attached to permission group

            ->where('permission_groups.fk_id', $this->model->id)
            ->where('permissions.group_by', 'permission_groups')
            ->get();

        return $permissionGroups;
    }

    public function getPermissions()
    {
        $permissions = DB::table('metas as permission_groups')
            ->join('metas as permissions', 'permission_groups.value', '=', 'permissions.fk_id')
            ->join('categories', 'categories.id', '=', 'permissions.value')

            ->where('permission_groups.table_name', 'users')
            ->where('permission_groups.fk_id', $this->model->id)
            ->where('permission_groups.key', 'permission_group_id')

            ->where('permissions.table_name', 'categories')
            ->where('permissions.key', 'permission_id')

            ->orderBy('categories.name', 'asc')
            ->select('categories.name')
            ->distinct()
            ->get();

        return $permissions->sortBy('name');
    }

    public function hasAccess($name)
    {
        return !empty($this->permissions->where('name', $name)->first());
    }

    public function hasAccesses($listName)
    {
        $isHasAccess = $this->permissions;

        foreach($listName as $x)
            $isHasAccess->where('name', $x);

        return !empty($isHasAccess);
    }

    public function getUser()
    {
        return $this->getModel();
    }

    public function hasPerson()
    {
        $user = $this->getUser();

        if (empty($user->person_id)) {
            $messages = 'Anda tidak terasosiasi dengan data civitas.';
            self::throwUnauthorizedException($messages);
        }
    }
}