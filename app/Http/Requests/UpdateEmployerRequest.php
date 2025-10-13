<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'departement_id'     => 'required|exists:departements,id',
            'nom'                => 'required|string|max:255',
            'prenom'             => 'required|string|max:255',
            'email'              => 'required',
            'contact'            => 'required',
            'montant_journalier' => 'required'
        ];
    }

    public function messages()
    {
        return [
     
            'email.required'           => 'Le mail est requis.',
            'contact.required'         => 'Le numéro est requis.',
             'nom.required'            => 'Le nom est requis.',
              'prenom.required'        => 'Le prenom est requis.',
        ];
    }
}