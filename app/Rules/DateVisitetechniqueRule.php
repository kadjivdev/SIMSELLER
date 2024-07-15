<?php

namespace App\Rules;

use App\Models\VisiteTechnique;
use App\tools\DateConverter;
use Illuminate\Contracts\Validation\Rule;

class DateVisitetechniqueRule implements Rule
{
    private $camions, $dateFin, $msg, $update,$libelle;
    public function __construct($camions, $dateFin,$libelle,$update=null)
    {
        $this->camions = $camions;
        $this->dateFin = $dateFin;
        $this->update = $update;
        $this->libelle = $libelle;
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
        $this->msg = "La date début ou fin coincide avec l'une des date d'une visite encours";
        return DateConverter::checkDate($value,$this->dateFin, $this->camions,$this->libelle,$this->update);
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
