<?php

namespace App\Http\Requests\Auth;

use App\Traits\Base;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegistrationRequest extends FormRequest
{
    use Base;
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed|min:8",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Base::validation($validator));
    }

    public function messages()
    {
        return [
            "name.required" => "Name is required",
            "email.required" => "Email is required",
            "email.email" => "Email is invalid",
            "email.unique" => "Email is already taken",
            "password.required" => "Password is required",
            "password.confirmed" => "Confirmed password is not matched",
            "password.min" => "Password must be 8 digit minimum"
        ];
    }
}
