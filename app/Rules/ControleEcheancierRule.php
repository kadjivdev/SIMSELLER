<?php

namespace App\Rules;

use App\Models\EcheanceCredit;
use App\Models\Vente;
use Illuminate\Contracts\Validation\Rule;

class ControleEcheancierRule implements Rule
{
    private $echeancier;
    private $vente;
    private $message;
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
        $recherche = EcheanceCredit::where('vente_id',$this->vente)->where('date', $value)->get();
        if($recherche){
            $this->message = "Cette date a été déjà défini";
            return true;
        }
        if($value > date('Y-m-d')){
            $this->message = "Vous avez une écheance en cours.";
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
        return 'The validation error message.';
    }
}
