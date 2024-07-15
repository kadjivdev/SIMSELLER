<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMarqueRequest extends FormRequest
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
        if($this->marque){
            return [
                'libelle' => ['required', Rule::unique('marques', 'libelle')->ignore($this->marque)],
            ];
        }else{
            return [
                'libelle' => ['required', 'string', 'max:255', 'unique:marques,libelle'],
            ];
        }
    }
}
