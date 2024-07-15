<?php

namespace App\Rules;

use App\Models\Recu;
use App\Models\BonCommande;
use Illuminate\Contracts\Validation\Rule;

class ActionQteRecuRule implements Rule
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
        $detailboncommandes = $this->boncommande->detailboncommandes()->get();
        $detailboncommandes = collect($detailboncommandes)->sum('qteCommander');

        if(count($recus) == 0){
            if(floatval($value) > floatval($detailboncommandes))
                return false;
            else
                return true;
        }else{
            if($this->recu == NULL){
                $somme = (collect($recus)->sum('tonnage') + $value);
                
                if(floatval($somme)  > floatval($detailboncommandes))
                    return false;
                else
                    return true;
            }else{
                $somme = ((collect($recus)->sum('tonnage') - $this->recu->tonnage) + $value);
                if(floatval($somme)  > floatval($detailboncommandes))
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
        return 'Désolé la quantité du bon commande déjà atteint, veuillez vérifier';
    }
}
