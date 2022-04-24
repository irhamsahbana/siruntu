<?php

namespace App\Http\Requests\Traits;

use Illuminate\Validation\Rule;

trait CourseMasterReqTrait
{
    protected function getRulesRefNo()
    {
        return [
            'nullable',
            'string',
            'max:255',
            Rule::unique('course_masters', 'ref_no')->ignore($this->id),
        ];
    }

    protected function getRulesName()
    {
        return ['required', 'string', 'max:255'];
    }
}