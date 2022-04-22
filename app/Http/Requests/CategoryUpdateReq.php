<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Http\Requests\Traits\CategoryReqTrait as ReqTrait;

class CategoryUpdateReq extends FormRequest
{
    use ReqTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'label' => $this->getRulesLabel(),
            'notes' => $this->getRulesNotes(),
            'group_by' => $this->getRulesGroupBy(),
            'name' => $this->getRulesName(),
        ];

        $rules['name'][] = Rule::unique('categories')->where(function ($query) {
                                $query->where('group_by', $this->group_by);
                            })->ignore($this->id);

        return $rules;
    }
}
