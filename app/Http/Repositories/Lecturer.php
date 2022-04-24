<?php

namespace App\Http\Repositories;

use App\Models\Person as Model;
use App\Models\Category;

class Lecturer extends Person
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        $this->filterByAccessControl('lecturer-create');
        parent::save();
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl('lecturer-delete');
        parent::delete();
    }

    public function get()
    {
        $this->filterByAccessControl('lecturer-read');
        return $this->model;
    }

    protected function generateData()
    {
        parent::generateData();

        $this->model->category_id = Category::where('name', 'lecturer')->first()->id;
    }

    protected function getPrefix()
    {
        return 'lecturer/';
    }
}