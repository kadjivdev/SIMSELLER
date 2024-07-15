<?php

namespace App\Rules;

use App\Models\Vendu;
use Illuminate\Contracts\Validation\Rule;

class VenduDoublonProduitStokValide implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $messageText;
    private $programmation_id;
    private $vente_id;
    public function __construct($programmation_id,$vente_id)
    {
        $this->programmation_id = $programmation_id;
        $this->vente_id = $vente_id;
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
        $vendu = Vendu::where('programmation_id',$this->programmation_id)->where('vente_id',$this->vente_id)->first();
        //Contrôle de doublon
        if($vendu){
            $this->messageText = 'Doublon! Ce produit est déjà dans la liste vente en cours';
            return false;
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
