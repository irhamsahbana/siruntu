<?php

namespace App\Http\Requests\Traits;

use Illuminate\Validation\Rule;

trait CourseReqTrait
{
    protected function getRulesCourseMasterId()
    {
        return [
            'required',
            'integer',
            'exists:course_masters,id',
            Rule::unique('courses', 'course_master_id')->where(function ($query) {
                $query->where('semester_id', $this->semester_id);
            })->ignore($this->id),
        ];
    }

    protected function getRulesSemesterId()
    {
        return [
            'required',
            'integer',
            Rule::exists('categories', 'id')
                ->where(function ($query) {
                    $query->where('group_by', 'semesters');
                }),
        ];
    }
}