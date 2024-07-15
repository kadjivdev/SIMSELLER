<?php

namespace App\Rules;

use App\Models\Vente;
use Illuminate\Contracts\Validation\Rule;

class CheckQuanteCde implements Rule
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
        $commandeclient = $this->vente->commandeclient;
        $totalVendu = 0;
        foreach ($commandeclient->ventes as $v){
            if($v->statut == 'Vendue')
                $totalVendu +=  $v->qteTotal;
        }
        $qteCder = $commandeclient->commanders()->sum('qteCommander');
        if(($qteCder - $totalVendu) < $value && !$this->vente->commandeclient->byvente){
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
        return 'Vous ne pouvez pas vendre au delà de la quantité restante de la commande.';
    }
}
