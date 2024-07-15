<?php

namespace App\Rules;

use App\Models\Fournisseur;
use Illuminate\Contracts\Validation\Rule;

class commercialiserRule implements Rule
{
    private $fournisseur;

    private $message = '';


    public function __construct($fournisseur)
    {
        $this->fournisseur = $fournisseur;

    }



    public function passes($attribute, $value)
    {

        $tab = $value;

        $fournisseur = Fournisseur::find($this->fournisseur);
        $produits = $fournisseur->produits()->whereIn('id', $tab)->get();
        //dd($animateurs);
        if(count($produits)==0){
            return true;

        }else{
            $error = '';
            foreach($produits as $produit){
                $error .= $produit->libelle.' , ' ;
            }
            $this->message = 'Les produits suivants sont déjà liés au fournisseur. '. $error;

            return false;
        }



    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
