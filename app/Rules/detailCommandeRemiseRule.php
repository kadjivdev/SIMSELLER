<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class detailCommandeRemiseRule implements Rule
{
    
    private $qteCommander, $pu;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($qteCommander, $pu)
    {
        $this->qteCommander = $qteCommander;
        $this->pu = $pu;
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
        if($value > ($this->qteCommander*$this->pu))
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
        return 'Désolé! La rémise est supérieure au montant.';
    }
}
