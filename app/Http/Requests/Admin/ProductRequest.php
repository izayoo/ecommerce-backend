<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        $nameValidation = isset($this->id)
            ? 'required|string|max:255|unique:products,id,'. $this->id
            : 'required|string|max:255|unique:products';

        return [
            'name' => $nameValidation,
            'description' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required',
            'dimensions' => 'required|string',
            'weight_in_grams' => 'required|integer',
            'product_category_id' => 'required|integer|exists:product_categories,id',
            'media_id' => 'required|integer|exists:media,id'
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
