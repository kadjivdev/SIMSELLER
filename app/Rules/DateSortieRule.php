<?php

namespace App\Rules;

use App\Models\Programmation;
use Illuminate\Contracts\Validation\Rule;

class DateSortieRule implements Rule
{
    private $programmation,$dateSortie, $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Programmation $programmation)
    {
        $this->programmmation = $programmation->dateprogrammer;
        $this->dateSortie = $programmation->dateSortie;
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

        if($this->dateSortie !== null ) {
            $this->message = "Attention La Date de sortie existe déjà !";
            return false;
        }

        if($this->programmation > $value) {
            $this->message = "Attention La date de sortie est Supérieure à la date de programmation !";
            return false;
        }
       
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return  $this->message;
    }
}
