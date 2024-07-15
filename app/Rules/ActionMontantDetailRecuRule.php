<?php

namespace App\Rules;

use App\Models\Recu;
use App\Models\DetailRecu;
use Illuminate\Contracts\Validation\Rule;

class ActionMontantDetailRecuRule implements Rule
{
    private $recu, $detailrecu;
    public function __construct(Recu $recu, DetailRecu $detailrecu = NULL)
    {
        $this->recu = $recu;
        $this->detailrecu = $detailrecu;
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
        $detailrecus = $this->recu->detailrecus()->get();

        if(count($detailrecus) == 0){
            if(floatval($value) > floatval($this->recu->montant))
                return false;
            else
                return true;
        }else{
            if($this->detailrecu == NULL){
                $somme = (collect($detailrecus)->sum('montant') + $value);
                
                if(floatval($somme)  > floatval($this->recu->montant))
                    return false;
                else
                    return true;
            }else{
                $somme = ((collect($detailrecus)->sum('montant') - $this->detailrecu->montant) + $value);
                if(floatval($somme)  > floatval($this->recu->montant))
                    return false;
                else
                    return true;
            }
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Désolé le montant du reçu déjà atteint, veuillez vérifier';
    }
}
