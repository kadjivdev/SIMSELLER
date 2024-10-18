<?php

namespace App\Rules;

use App\Models\Reglement;
use App\Models\Vente;
use App\tools\CompteTools;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ReglementMontantRule implements Rule
{
    private $vente, $reglement,$srcReg,$message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Vente $vente,$srcReg,Reglement $reglement = NULL)
    {
        $this->srcReg = $srcReg;
        $this->vente = $vente;
        $this->reglement = $reglement;
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
        $reglements = $this->vente->reglements()->get();
      
        if(count($reglements) == 0){
            if(floatval($value) > floatval($this->vente->montant))
                return false;
            else
                return true;
        }else{

            if($this->reglement == NULL){
                $somme = (collect($reglements)->sum('montant') + $value);
                
                if(floatval($somme)  > floatval($this->vente->montant))
                    return false;
                else{
                    if($this->srcReg == 'indirect'){
                        //Voir le client à de solde
                        $compte = $this->vente->commandeclient->client->compteClients;
                        if(count($compte) > 0){
                            if((floatval($compte[0]->solde) - floatval($value)) < 0){
                                $this->message = "Fond du compte client inssuffisant.";
                                return false;
                            } 
                            else{
                                return true;
                            }
                        }
                        else{
                                CompteTools::addCompte($this->vente->commandeclient->client->id,Auth::user()->id);
                                $this->message = "Fond du compte client inssuffisant.";
                                return false;
                        }
                    }
                    else{
                        return true;
                    }
                }
                    
            }else{
                $somme = ((collect($reglements)->sum('montant') - $this->reglement->montant) + $value);
                if(floatval($somme)  > floatval($this->vente->montant))
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
        if($this->message ) 
            return $this->message; 
        else 
            return 'Désolé le montant du règlement est déjà atteint, veuillez vérifier.';
    }
}
