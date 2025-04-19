<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BillingAddressRequest extends FormRequest
{
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "num" => "required|string|between:1,5",
            "street" => "required|string|between:4,50",
            "zip" => "required|string|size:5",
            "city" => "required|string|between:2,50",
            "country" => "required|string|between:2,50"
        ];
    }

    public function messages(): array
    {
        return [
            "required" => "Le champ :attribute est requis.",
            "string" => "Le champ :attribute doit être une chaîne de caractères.",
            "between" => "Le champ :attribute doit contenir entre :min et :max caractères.",
            "size" => "Le champ :attribute doit contenir exactement :size caractères."
        ];
    }
}
