<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccessRightUpdateReq extends FormRequest
{
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
        return [
            'label' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'label')->where(function ($query) {
                    return $query->where('group_by', 'permission_groups');
                })->ignore($this->id),
            ],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }
}
