<?php

namespace App\Http\Requests\User;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    use ResponseTrait;

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
            'name' => 'required',
            'password' => 'required|min:8',
            'username' => 'required|unique:users'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        foreach ($validator->errors()->toArray() as $value) {
            $_messages[] = $value[0];
        }
//        return ($validator->errors()->toJson());
        throw new HttpResponseException($this->badRequestResponse([
            'status' => 0,
            'message' => $_messages
        ]));
    }
}
