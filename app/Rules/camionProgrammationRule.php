<?php

namespace App\Rules;

use App\Models\Camion;
use Illuminate\Contracts\Validation\Rule;

class camionProgrammationRule implements Rule
{

    private $qteprogrammer, $camion;
    public function __construct($qteprogrammer)
    {
        $this->qteprogrammer = $qteprogrammer;
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
        $this->camion = Camion::findOrFail($value);

        if($this->camion->tonnage == NULL){
            return true;
        }else{
            if(floatval($this->camion->tonnage) < floatval($this->qteprogrammer))
                return false;
            else
                return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Désolé le camion ne peut pas charger plus que '.$this->camion->tonnage.' tonnes';
    }
}
