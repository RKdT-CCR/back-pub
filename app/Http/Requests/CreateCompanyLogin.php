<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CreateCompanyLogin extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        try{
            throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
        }catch(\Exception $ex){
            \Log::notice("[".__METHOD__."] CREATE COMPANY REQUEST ERROR [".$validator->messages()."]");
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }

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
            'email'=> [
                'required',
                'unique:App\Models\User,email',
                'email'
            ],
            'password' => 'required',
            'c_password' => 'required|same:password',
            'cnpj' => 'required|regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/',
            'industry' => 'required|min:5',
            'employees_number' => 'required'
        ];

    }
}
