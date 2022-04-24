<?php

namespace App\Http\Repositories;

use App\Models\Course as Model;

class Course extends AbstractRepository
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        $this->filterByAccessControl('course-create');
        parent::save();
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl('course-delete');
        parent::delete($permanent);
    }

    public function get()
    {
        $this->filterByAccessControl('course-read');
        return $this->model;
    }
}