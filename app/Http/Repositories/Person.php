<?php

namespace App\Http\Repositories;

use App\Models\Person as Model;

class Person extends AbstractRepository
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        parent::save();
    }

    public function delete($permanent = null)
    {
        parent::delete();
    }

    public function get()
    {
        return $this->model;
    }

    protected function beforeSave()
    {
       $this->generateData();
    }

    protected function generateData()
    {
        if (empty($this->model->ref_no))
            $this->model->ref_no = $this->generateRefNo($this->model->getTable(), 4, $this->getPrefix(), $this->getPostfix());
    }
}