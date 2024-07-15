<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterlocuteurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:255', 'unique:interlocuteurs,telephone'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:interlocuteurs,email'],
            'fournisseur_id' => ['required'],
            'qualification' => ['required', 'string', 'max:255'],
        ];
    }
}
