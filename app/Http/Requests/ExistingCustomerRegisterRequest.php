<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Valide l'enregistrement d'un utilisateur ayant déjà un compte client.
 */
class ExistingCustomerRegisterRequest extends FormRequest
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
            "email" => "required|email|exists:customers,email",
            "password" => "required|string|between:6,50",
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
            "email" => "Adresse mail invalide.",
            "exists" => "Aucun client n'est associé avec cette adresse mail.",
            "string" => "Le champ :attribute doit être une chaîne de caractères.",
            "between" => "Le champ :attribute doit être compris entre :min et :max caractères.",
        ];
    }
}
