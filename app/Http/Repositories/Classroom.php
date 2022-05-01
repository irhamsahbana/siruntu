<?php

namespace App\Http\Repositories;

use App\Models\Classroom as Model;
use App\Models\Category;

class Classroom extends AbstractRepository
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        $this->filterByAccessControl('classroom-create');
        parent::save();
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl('classroom-delete');
        parent::delete($permanent);
    }

    public function get()
    {
        $this->filterByAccessControl('classroom-read');
        return $this->model;
    }

    protected function beforeSave()
    {
        if (empty($this->model->mode_id))
            $this->model->mode_id = Category::where('group_by', 'classroom_modes')
                                            ->where('name', 'normal'
                                            )->first()->id;
    }
}