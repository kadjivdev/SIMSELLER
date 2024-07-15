<?php

namespace App\Rules;

use App\Models\Assurance;
use App\tools\DateConverter;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DateAssuranceRule implements Rule
{
    private $camions, $dateFin, $msg, $update;
    public function __construct($camions, $dateFin,$update=null)
    {
        $this->camions = $camions;
        $this->dateFin = $dateFin;
        $this->update = $update;
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
        $this->msg = "La date début ou fin coincide avec l'une des date d'un assurance encours";
        return DateConverter::checkDateAss($value,$this->dateFin, $this->camions,$this->update);

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msg;
    }
}
