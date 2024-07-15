<?php

namespace App\Rules;

use App\Models\DetailBonCommande;
use App\Models\Programmation;
use Illuminate\Contracts\Validation\Rule;

class QteProgrammationRule implements Rule
{

    private $detailboncommande, $programmation;
    public function __construct(DetailBonCommande $detailboncommande, Programmation $programmation = NULL)
    {
        $this->detailboncommande = $detailboncommande;
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
        $programmations = Programmation::where('detail_bon_commande_id', $this->detailboncommande->id)->where('statut', 'Valider')->get();

        if(count($programmations) == 0){
            if(floatval($value) > floatval($this->detailboncommande->qteCommander))
                return false;
            else
                return true;
        }else{
            if($this->programmation == NULL){
                $somme = (collect($programmations)->sum('qteprogrammer') + $value);
                //dd($somme);
                if($somme  <= $this->detailboncommande->qteCommander)
                    return true;
                else
                    return false;
            }else{
                $somme = ((collect($programmations)->sum('qteprogrammer') - $this->programmation->qteprogrammer) + $value);

                if($somme  > $this->detailboncommande->qteCommander)
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
        return 'Désolé la quantité programmé a dépassé la quantité du produit commander';
    }
}
