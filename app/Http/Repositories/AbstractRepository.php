<?php

namespace App\Http\Repositories;

use Illuminate\Database\Eloquent\Model;

use App\Libs\HasAccessControl;
use App\Libs\RefNoGenerator;

abstract class AbstractRepository
{
    use HasAccessControl, RefNoGenerator;

    protected Model $model;
    protected $original;

    public function __construct(Model $model)
    {
        $this->model = $model;

        if(!empty($this->model))
            $this->original = $this->model->getOriginal();
    }

    public function toArray()
    {
        return $this->model->toArray();
    }

    public function save()
    {
        $this->beforeSave();
        $this->model->save();
        $this->afterSave();
    }

    protected function beforeSave() {}
    protected function afterSave() {}

    public function delete($permanent = null)
    {
        $this->beforeDelete();

        if ($permanent) {
            $this->model->forceDelete();
        } else {
            $this->model->delete();
        }

        $this->afterDelete();
    }

    protected function beforeDelete() {}
    protected function afterDelete() {}
}