<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            "name" => "required|string|between:4,50",
            "description" => "nullable|string|between:4,255",
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "required" => "Le champ :attribute est requis.",
            "string" => "Le champ :attribute doit être une chaîne de caractères.",
            "between" => "Le champ :attribute doit contenir entre :min et :max caractères.",
            "max" => "Le champ :attribute ne doit pas dépasser :max caractères.",
        ];
    }
}
