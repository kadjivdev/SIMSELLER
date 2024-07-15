<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBanqueRequest extends FormRequest
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
            'sigle' => ['required', 'string', 'max:255', 'unique:banques,sigle'],
            'raisonSociale' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:255', 'unique:banques,telephone'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:banques,email'],
            'adresse' => ['required', 'string'],
            'interlocuteur' => ['required', 'string'],
        ];
    }
}
