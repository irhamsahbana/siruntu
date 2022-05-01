<?php

namespace App\Http\Repositories\Finder;

use App\Models\Classroom as Model;

class ClassroomFinder extends AbstractFinder
{
    private $courseId;

    public function __construct()
    {
        $this->query = Model::select(
                                'classrooms.id',
                                'classrooms.course_id',
                                'classrooms.mode_id',
                                'classrooms.name',

                                'courses.ref_no AS course_ref_no',
                                'courses.name AS course_name',

                                'modes.label AS mode_label'
                            );

        $this->query->join('courses', 'courses.id', '=', 'classrooms.course_id');
        $this->query->join('categories AS modes', 'modes.id', '=', 'classrooms.mode_id');
    }

    public function setCourse($courseId)
    {
        $this->courseId = $courseId;
    }

    private function whereCourse()
    {
        if(!empty($this->courseId))
            $this->query->where('classrooms.course_id', $this->courseId);
    }

    public function whereKeyword()
    {
        if(!empty($this->keyword)) {
            $list = explode(' ', $this->keyword);
            $list = array_map('trim', $list);

            $this->query->where(function($query) use ($list) {
                foreach($list as $x) {
                    $pattern = '%' . $x . '%';
                    $query->orWhere('classrooms.name', 'like', $pattern);
                }
            });
        }
    }

    public function doQuery()
    {
        $this->whereCourse();
        $this->whereKeyword();
    }
}