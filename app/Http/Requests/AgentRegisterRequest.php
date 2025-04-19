<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AgentRegisterRequest extends FormRequest // Permet aux administrateurs de créer des agents.
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole(Role::ADMIN);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email",
            "password" => "required|string|min:8",
            "phone" => "required|string|max:255",
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
            "max" => "Le champ :attribute ne doit pas dépasser :max caractères.",
            "email" => "Le champ :attribute doit être une adresse e-mail valide.",
            "unique" => "Cette adresse e-mail est déjà utilisée.",
            "min" => "Le champ :attribute doit contenir au moins :min caractères.",
        ];
    }
}
