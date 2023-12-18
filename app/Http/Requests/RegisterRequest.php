<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'country_code' => 'required|string|size:3',
            'mobile_no' => 'required|string|size:10',
            'birthdate' => 'required|date|date_format:Y-m-d',
            'nationality' => 'string',
            'gender' => ['string','size:1',Rule::in(['m','f'])],
            'password' => 'required|string|confirmed',
            'password_confirmation ' => 'string',
            'verification_url' => 'required|string'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->getMessageBag()->getMessages()
        ], 422));
    }

    public function messages(): array
    {
        return [
            'birthdate.date_format' => 'Please follow the format YYYY-MM-DD.'
        ];
    }
}
