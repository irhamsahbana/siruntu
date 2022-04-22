<?php

namespace App\Http\Repositories;

use Illuminate\Support\Str;

use App\Models\Category as Model;

class Category extends AbstractRepository
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        $this->filterByAccessControl(sprintf('category-%s-create', $this->model->group_by));
        parent::save();
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl(sprintf('category-%s-delete', $this->model->group_by));
        parent::delete($permanent);
    }

    protected function beforeSave()
    {
        $this->generateData();
    }

    private function generateData()
    {
        $this->model->name = Str::slug($this->model->label);
    }

    public function get()
    {
        $this->filterByAccessControl(sprintf('category-%s-read', $this->model->group_by));

        return $this->model;
    }
}