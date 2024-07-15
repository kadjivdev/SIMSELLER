<?php

namespace App\Rules;

use App\Models\Programmation;
use App\Models\Vendu;
use Illuminate\Contracts\Validation\Rule;

class CheckStock implements Rule
{
    private $programmation_id;
    private $messageText;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($programmation_id)
    {
        $this->programmation_id = $programmation_id;
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
        if($this->programmation_id){
            $programmation = Programmation::find($this->programmation_id);
            $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
            $stockDispo = $programmation->qtelivrer - $qteVendu;
            if($value > $stockDispo){
                $this->messageText = "Le stock disponible ($stockDispo) insuffisant.";
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->messageText;
    }
}
