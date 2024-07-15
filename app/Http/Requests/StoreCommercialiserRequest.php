<?php

namespace App\Http\Requests;

use App\Rules\commercialiserRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommercialiserRequest extends FormRequest
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
            'produit_id' => ['required'],
            'fournisseur_id' => ['required'],
        ];
    }
}
