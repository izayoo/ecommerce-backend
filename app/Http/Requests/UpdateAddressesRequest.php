<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressesRequest extends FormRequest
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
            "shipping_address" => 'required|array',
            "billing_address" => 'required|array',
            "shipping_address.region" => "required|string",
            "shipping_address.province" => "required|string",
            "shipping_address.city" => "required|string",
            "shipping_address.barangay" => "required|string",
            "shipping_address.address1" => "required|string",
            "shipping_address.address2" => "string|nullable",
            "billing_address.region" => "required|string",
            "billing_address.province" => "required|string",
            "billing_address.city" => "required|string",
            "billing_address.barangay" => "required|string",
            "billing_address.address1" => "required|string",
            "billing_address.address2" => "string|nullable",
        ];
    }
}
