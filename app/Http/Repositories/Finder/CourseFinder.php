<?php

namespace App\Http\Repositories\Finder;

use App\Models\Course as Model;

class CourseFinder extends AbstractFinder
{
    public function __construct()
    {
        $this->query = Model::select(
                                'courses.id',
                                'courses.course_master_id',
                                'courses.semester_id',
                                'courses.ref_no',
                                'courses.name',
                                'semesters.label as semester_label',
                            );

        $this->query->join('categories AS semesters', 'semesters.id', '=', 'courses.semester_id');
    }

    public function whereKeyword()
    {
        if(!empty($this->keyword)) {
            $list = explode(' ', $this->keyword);
            $list = array_map('trim', $list);

            $this->query->where(function($query) use ($list) {
                foreach($list as $x) {
                    $pattern = '%' . $x . '%';
                    $query->orWhere('courses.ref_no', 'like', $pattern);
                    $query->orWhere('courses.name', 'like', $pattern);
                }
            });
        }
    }

    public function doQuery()
    {
        $this->whereKeyword();
    }
}