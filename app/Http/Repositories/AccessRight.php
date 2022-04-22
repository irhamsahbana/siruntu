<?php

namespace App\Http\Repositories;

use Illuminate\Support\Str;

use App\Models\Category as Model;
use App\Models\Meta;

class AccessRight extends AbstractRepository
{
    private $permissions = [];
    private $_delete_permissions;

    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        $this->filterByAccessControl('access-right-create');
        parent::save();
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl('access-right-delete');
        parent::delete($permanent);
    }

    public function get()
    {
        $this->filterByAccessControl('access-right-read');
        return $this->model;
    }

    protected function afterDelete()
    {
        // Get all permission
        Meta::where([
            'fk_id' => $this->model->id,
            'table_name' => $this->model->getTable(),
            'key' => 'permission_id'
        ])->delete();
    }

    protected function beforeSave()
    {
        $this->generateData();
    }

    protected function afterSave()
    {
        $this->savePermissions();
        $this->deletePermissions();
    }

    private function generateData()
    {
        if(empty($this->model->group_by))
            $this->model->group_by = 'permission_groups';

        $this->model->name = Str::slug($this->model->label);
    }

    public function addPermission(int $permissionId)
    {
        $this->permissions[] = $permissionId;
    }

    private function savePermissions()
    {
        // Get all permission
        $list = Meta::where([
            'fk_id' => $this->model->id,
            'table_name' => $this->model->getTable(),
            'key' => 'permission_id'
        ])->get();

        foreach ($this->permissions as $x) {
            // Create blank meta
            $permission = new Meta;

            // If not empty, get one of them(overwrite)
            if ($list->isNotEmpty())
                $permission = $list->shift();

            // Fill with correct information
            $permission->fk_id = $this->model->id;
            $permission->table_name = $this->model->getTable();
            $permission->key = 'permission_id';
            $permission->value = $x;
            $permission->save();
        }

        $this->_delete_permissions = $list;
    }

    private function deletePermissions()
    {
        $list = $this->_delete_permissions;

        if($list != null && $list->isNotEmpty())
            Meta::whereIn('id', $list->pluck('id'))->delete();
    }
}