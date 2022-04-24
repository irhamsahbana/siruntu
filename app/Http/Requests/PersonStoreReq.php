<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Traits\PersonReqTrait as ReqTrait;

class PersonStoreReq extends FormRequest
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
        return [
            'ref_no' => $this->getRulesRefNo(),
            'name' => $this->getRulesName(),
            'email' => $this->getRulesEmail(),
        ];
    }
}
