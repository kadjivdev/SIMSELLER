<?php

namespace App\Rules;

use App\Models\Vente;
use Illuminate\Contracts\Validation\Rule;

class CheckQuantite implements Rule
{
    private $vente;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Vente $vente)
    {
        $this->vente = $vente;
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
        $total = $this->vente->vendus()->sum('qteVendu');
        $qteTotal = $this->vente->qteTotal ? :$value;
        if(($total+$value) > $qteTotal){
            return false;
        }
        else{
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
        return 'Cette quantité ajoutée à celle en cours dépasse la quantité de la vente';
    }
}
