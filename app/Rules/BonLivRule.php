<?php

namespace App\Rules;

use App\Models\Programmation;
use Illuminate\Contracts\Validation\Rule;

class BonLivRule implements Rule
{
   
    private $programmation;
     /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Programmation $programmation)
    {
        //
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
        //
        if(intval($this->programmation->bl_gest) == intval($value))
            return true;
        else
            return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Le bordéreau de livraison saisie n\'est pas valide.  Veuillez contacter votre Gestionnaire pour avoir la bonne information';
    }
}
