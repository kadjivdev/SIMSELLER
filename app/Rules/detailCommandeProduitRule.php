<?php

namespace App\Rules;

use App\Models\DetailBonCommande;
use Illuminate\Contracts\Validation\Rule;

class detailCommandeProduitRule implements Rule
{
    private $commande_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($commande_id)
    {
        $this->commande_id = $commande_id;
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
        $detailboncommande = DetailBonCommande::where('produit_id', $value)
            ->where('bon_commande_id',$this->commande_id)->get();

        if(COUNT($detailboncommande) > 0)
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
        return 'Produit déjà ajouté pour le bon commande.';
    }
}
