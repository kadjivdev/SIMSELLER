<?php

namespace App\Rules;

use App\Models\Camion;
use Illuminate\Contracts\Validation\Rule;

class UniqueImmatriculationCamionRule implements Rule
{

    private $immatriculationRemorque;
    public $message;
    private $id;
    public function __construct($immatriculationRemorque, $id=null)
    {
        $this->immatriculationRemorque = $immatriculationRemorque;
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value == $this->immatriculationRemorque){
            $this->message = "Immatriculation tracteur égale à celle de la remorque";
            return false;
        }
        else{
            $camion = Camion::where('immatriculationTracteur', $this->immatriculationRemorque)->first();
            if($camion){
                if($camion->id == $this->id)
                    return true;
                else{
                    $this->message = 'Immatriculation remorque déjà utilisée pour un tracteur';
                    return false;
                }
            }
            else{
                $camion = Camion::where('immatriculationRemorque', $value)->first();
                if($camion){
                    if($camion->id == $this->id)
                        return true;
                    else{
                        $this->message = 'Immatriculation tracteur déjà utilisée pour une remorque';
                        return false;
                    }
                }
                else{
                    return true;
                }
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
