<?php

namespace App\Http\Repositories;

use App\Models\Classroom as Model;

class Classroom extends AbstractRepository
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }
}