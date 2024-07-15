<?php

namespace App\Http\Requests;

use App\Models\CategorieFournisseur;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class UpdateCategorieFournisseurRequest extends FormRequest
{
    protected $id;

    public function __construct(){
        
    }

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
            'libelle' => ['required', Rule::unique('categorie_fournisseurs', 'libelle')->ignore($this->categoriefournisseur)],
        ];
    }
}
