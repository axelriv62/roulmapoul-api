<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class HandoverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('create', \App\Models\Handover::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'datetime' => 'required|date_format:Y-m-d H:i:s',
            'mileage' => 'required|numeric|min:0',
            'fuel_level' => 'required|numeric|min:0',
            'interior_condition' => 'nullable|string|between:0,500',
            'exterior_condition' => 'nullable|string|between:0,500',
            'comment' => 'nullable|string|between:0,500',
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
            'integer' => 'Le champ :attribute doit être un entier.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'between' => 'Le champ :attribute doit contenir entre :min et :max caractères.',
            'date_format' => 'Le champ :attribute doit être au format :format.',
        ];
    }
}
