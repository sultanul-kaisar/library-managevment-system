<?php

namespace App\Http\Requests\Books;

use App\Traits\Base;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddBookRequest extends FormRequest
{
    use Base;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|unique:books",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Base::validation($validator));
    }

    public function messages()
    {
        return [
            "title.required" => "Book title is required",
            "title.unique" => "Book title is already exits",
        ];
    }
}
