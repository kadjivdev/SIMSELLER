<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class AvoirRule implements Rule
{
    private $user;

    private $message = '';


    public function __construct(User $user)
    {
        $this->user = $user;

    }



    public function passes($attribute, $value)
    {

        $tab = $value;

        $users = $this->user;
        $roles = $users->roles()->whereIn('id', $tab)->get();
        //dd($animateurs);
        if(count($roles)==0){
            return true;

        }else{
            $error = '';
            foreach($roles as $role){
                $error .= $role->libelle.' , ' ;
            }
            $this->message = 'L\'Utilisateur possède déjà les rôles suivants. '. $error;

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
