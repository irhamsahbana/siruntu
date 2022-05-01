<?php

namespace App\Http\Requests\Traits;

use Illuminate\Validation\Rule;

trait ClassroomReqTrait
{
    protected function getRulescourseId()
    {
        return [
            'required',
            'integer',
            'exists:courses,id'
        ];
    }

    protected function getRulesModeId()
    {
        return [
            'nullable',
            'integer',
            Rule::exists('categories', 'id')
                ->where(function ($query) {
                    $query->where('group_by', 'classroom_modes');
                }),
        ];
    }

    protected function getRulesname()
    {
        return [
            'required',
            'string',
            'max:255',
            Rule::unique('classrooms', 'name')->where(function ($query) {
                $query->where('course_id', $this->course_id);
            })->ignore($this->id),
        ];
    }
}