<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autoriser tout utilisateur authentifié (à adapter si besoin)
    }

    public function rules(): array
    {
        return [
            'departement_id'     => 'required|exists:departements,id',
            'nom'                => 'required|string|max:255',
            'prenom'             => 'required|string|max:255',
            'email'              => 'required|email|unique:employers,email',
            'contact'            => 'required|string|unique:employers,contact',
            'montant_journalier' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'departement_id.required' => 'Veuillez sélectionner un département.',
            'email.unique'            => 'Cet email existe déjà.',
            'contact.unique'          => 'Ce numéro existe déjà.',
        ];
    }
}
