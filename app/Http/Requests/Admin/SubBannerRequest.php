<?php

namespace App\Http\Requests\Admin;

use App\Enum\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SubBannerRequest extends FormRequest
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
            '*.media_id' => 'required|exists:media,id',
            '*.banner_type_id' => ['required','exists:banner_types,id',Rule::in([
                Constants::LEFT_SUB_BANNER,Constants::RIGHT_SUB_BANNER])],
            '*.url_redirect' => 'string'
        ];
    }

    public function messages()
    {
        return [
            '*.media_id' => 'The media field is required.',
            '*.banner_type_id' => 'The banner type field is required.'
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
