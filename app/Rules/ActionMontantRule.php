<?php

namespace App\Rules;

use App\Models\BonCommande;
use App\Models\Recu;
use Illuminate\Contracts\Validation\Rule;

class ActionMontantRule implements Rule
{
    private $boncommande, $recu;
    public function __construct(BonCommande $boncommande, Recu $recu = NULL)
    {
        $this->boncommande = $boncommande;
        $this->recu = $recu;
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
        $recus = $this->boncommande->recus()->get();

        if(count($recus) == 0){
            if(floatval($value) > floatval($this->boncommande->montant))
                return false;
            else
                return true;
        }else{
            if($this->recu == NULL){
                $somme = (collect($recus)->sum('montant') + $value);
                
                if(floatval($somme)  > floatval($this->boncommande->montant))
                    return false;
                else
                    return true;
            }else{
                $somme = ((collect($recus)->sum('montant') - $this->recu->montant) + $value);
                if(floatval($somme)  > floatval($this->boncommande->montant))
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
        return 'Désolé le montant du bon commande déjà atteint, veuillez vérifier';
    }
}
