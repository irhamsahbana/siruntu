<?php

namespace App\Http\Repositories;

use App\Models\CourseMaster as Model;

class CourseMaster extends AbstractRepository
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        $this->filterByAccessControl('course-master-create');
        parent::save();
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl('course-master-delete');
        parent::delete($permanent);
    }

    public function get()
    {
        $this->filterByAccessControl('course-master-read');
        return $this->model;
    }

    protected function beforeSave()
    {
        $this->generateData();
    }

    private function generateData()
    {
        if (empty($this->model->ref_no))
            $this->model->ref_no = $this->generateRefNo($this->model->getTable(), 4, $this->getPrefix(), $this->getPostFix());
    }

    protected function getPrefix()
    {
        return 'course-master/';
    }
}