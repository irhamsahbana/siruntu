<?php

namespace App\Http\Requests\Traits;

use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

trait CategoryReqTrait
{
    protected static $allowed = ['semester'];

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => Str::slug($this->label),
        ]);
    }

    protected function getRulesLabel()
    {
        return ['required', 'string', 'max:255'];
    }

    protected function getRulesNotes()
    {
        return ['nullable', 'string', 'max:255'];
    }

    protected function getRulesGroupBy()
    {
        return [
            'required',
            'string',
            'max:255',
            Rule::in(self::$allowed),
        ];
    }

    protected function getRulesName()
    {
        return ['required', 'string', 'max:255'];
    }
}