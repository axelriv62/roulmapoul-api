<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LicenseRequest extends FormRequest
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
            "num" => "required|string|between:1;15",
            "birthday" => "required|date|date_format:Y-m-d",
            "acquirement_date" => "required|date|date_format:Y-m-d",
            "distribution_date" => "required|date|date_format:Y-m-d",
            "country" => "required|string|between:2,50",
        ];
    }

    /**
     * Customise les messages de validation.
     *
     * @return array<string, string> les messages de validation.
     */
    public function messages(): array
    {
        return [
            "required" => "Le champ :attribute est requis.",
            "string" => "Le champ :attribute doit être une chaîne de caractères.",
            "between" => "Le champ :attribute doit contenir entre :min et :max caractères.",
            "date" => "Le champ :attribute doit être une date valide.",
            "date_format" => "Le champ :attribute doit être au format :format."
        ];
    }
}
