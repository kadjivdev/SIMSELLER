<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFournisseurRequest extends FormRequest
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
        if($this->photo){
            return [
                'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
                'sigle' => ['required', 'string', 'max:255', 'unique:fournisseurs,sigle'],
                'raisonSociale' => ['required', 'string', 'max:255'],
                'telephone' => ['required', 'string', 'max:255', 'unique:fournisseurs,telephone'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:fournisseurs,email'],
                'adresse' => ['required', 'string'],
                'categorie_fournisseur_id' => ['required'],
            ];
        }else{
            return [
                'sigle' => ['required', 'string', 'max:255', 'unique:fournisseurs,sigle'],
                'raisonSociale' => ['required', 'string', 'max:255'],
                'telephone' => ['required', 'string', 'max:255', 'unique:fournisseurs,telephone'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:fournisseurs,email'],
                'adresse' => ['required', 'string'],
                'categorie_fournisseur_id' => ['required'],
            ];
        };

    }
}
