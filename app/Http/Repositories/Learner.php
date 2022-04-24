<?php

namespace App\Http\Repositories;

use App\Models\Person as Model;
use App\Models\Category;

class Learner extends Person
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        $this->filterByAccessControl('learner-create');
        parent::save();
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl('learner-delete');
        parent::delete();
    }

    public function get()
    {
        $this->filterByAccessControl('learner-read');
        return $this->model;
    }

    protected function generateData()
    {
        parent::generateData();

        $this->model->category_id = Category::where('name', 'learner')->first()->id;
    }

    protected function getPrefix()
    {
        return 'learner/';
    }
}