<?php

namespace App\Rules;

use App\Models\Programmation;
use Illuminate\Contracts\Validation\Rule;

class QteLivraisonRule implements Rule
{

    private $programmation; 
    public function __construct(Programmation $programmation)
    {
        $this->programmation = $programmation;
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
        if(intval($this->programmation->qteprogrammer) < intval($value))
            return false;
        else
            return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La quantité livré est supérieure à la quantité programmé!';
    }
}
