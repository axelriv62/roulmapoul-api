<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RentalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // TODO : Implémenter la logique d'autorisation
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'car_plate' => 'required|exists:cars,plate',
            'warranty_id' => 'nullable|exists:warranties,id',
            'options' => 'nullable|array',
            'options.*' => 'integer|exists:options,id',
            'start' => 'required|date_format:Y-m-d|after_or_equal:today',
            'end' => 'required|date_format:Y-m-d|after:start',
        ];
    }

    /**
     * Get the custom messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est requis.',
            'exists' => 'Le champ :attribute doit exister dans la base de données.',
            'date_format' => 'Le champ :attribute doit être au format :format.',
            'after_or_equal' => 'La date de début de la location doit être aujourd\'hui ou une date ultérieure.',
            'after' => 'La fin de la location doit se faire après le début.',
        ];
    }
}
