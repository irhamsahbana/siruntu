<?php

namespace App\Http\Repositories\Finder;

use App\Models\CourseMaster as Model;

class CourseMasterFinder extends AbstractFinder
{
    public function __construct()
    {
        $this->query = Model::select('id', 'ref_no', 'name');
    }

    public function whereKeyword()
    {
        if(!empty($this->keyword)) {
            $list = explode(' ', $this->keyword);
            $list = array_map('trim', $list);

            $this->query->where(function($query) use ($list) {
                foreach($list as $x) {
                    $pattern = '%' . $x . '%';
                    $query->orWhere('course_masters.ref_no', 'like', $pattern);
                    $query->orWhere('course_masters.name', 'like', $pattern);
                }
            });
        }
    }

    public function doQuery()
    {
        $this->whereKeyword();
    }
}