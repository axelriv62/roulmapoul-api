<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'first_name' => 'required|string|between:4,50',
            'last_name' => 'required|string|between:4,50',
            'email' => 'required|string|email|max:255|unique:customers,email,'.$this->route('id'),
            'phone' => 'required|string|between:4,50',
            'num' => 'required|string|between:1,5',
            'street' => 'required|string|between:4,50',
            'zip' => 'required|string|size:5',
            'city' => 'required|string|between:2,50',
            'country' => 'required|string|between:2,50',
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
            'required' => 'Le champ :attribute est requis.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'between' => 'Le champ :attribute doit contenir entre :min et :max caractères.',
            'size' => 'Le champ :attribute doit contenir :size caractères.',
            'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
            'unique' => 'Cette adresse e-mail est déjà utilisée.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            'date_format' => 'Le champ :attribute doit être au format :format.',
            'before_or_equal' => 'Veuillez entrer une date valide.',
        ];
    }
}
