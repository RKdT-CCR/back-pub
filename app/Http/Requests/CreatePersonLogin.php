<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class CreatePersonLogin extends FormRequest
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

    protected function failedValidation(Validator $validator)
    {
        try{
            throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
        }catch(\Exception $ex){
            \Log::notice("[".__METHOD__."] CREATE PERSON REQUEST ERROR [".$validator->messages()."]");
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
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
            'cpf' => 'required|regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/',
            'birth' => 'required|date_format:Y-m-d',
            'educational_level' => 'required',
            'occupation_area' => 'required',
            'number' => 'required'
        ];
    }
}
