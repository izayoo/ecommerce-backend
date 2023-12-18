<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserDetailRequest extends FormRequest
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
        $id = auth()->user()->getAuthIdentifier();

        return [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$id,
            'country_code' => 'required|string|size:3',
            'mobile_no' => 'required|string|size:10',
            'birthdate' => 'required|date',
            'nationality' => 'required|string',
            'gender' => ['required','string','size:1',Rule::in(['m','f'])],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->getMessageBag()->getMessages()
        ], 422));
    }
}
