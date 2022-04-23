<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Traits\CourseMasterReqTrait as ReqTrait;

class CourseMasterUpdateReq extends FormRequest
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
            'ref_no' => $this->getRulesRefNo(),
            'name' => $this->getRulesName(),
        ];

        return $rules;
    }
}
