<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CampaignRequest extends FormRequest
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
            ? 'required|string|max:255|unique:campaigns,id,' . $this->id
            : 'required|string|max:255|unique:campaigns';

        return [
            'name' => $nameValidation,
            'subtitle' => 'required|string',
            'draw_mechanics' => 'string|nullable',
            'description' => 'required|string|nullable',
            'product_id' => 'required|exists:products,id',
            'max_tickets' => 'required|integer',
            'campaign_category_id' => 'required|exists:campaign_categories,id',
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d',
            'draw_date' => 'required|date|date_format:Y-m-d',
            'is_featured' => 'required|boolean',
            'media_id' => 'required|integer|exists:media,id',
            'banner_id' => 'required|integer|exists:media,id'
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
