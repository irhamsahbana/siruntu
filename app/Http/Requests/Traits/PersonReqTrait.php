<?php

namespace App\Http\Requests\Traits;

use Illuminate\Validation\Rule;

trait PersonReqTrait
{
    protected function getRulesRefNo()
    {
        return [
            'nullable',
            'string',
            'max:255',
            Rule::unique('people', 'ref_no')->ignore($this->id),
        ];
    }

    protected function getRulesName()
    {
        return ['required', 'string', 'max:255'];
    }

    protected function getRulesEmail()
    {
        return ['nullable', 'string', 'email', 'max:64'];
    }
}