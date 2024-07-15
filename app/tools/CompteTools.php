<?php

namespace App\tools;

use App\Models\CompteClient;

class CompteTools
{
    public static function addCompte($client_id,$user_id){
        return CompteClient::create([
           'client_id'=>$client_id,
           'user_id'=>$user_id,
           'solde'=>0
        ]);
    }
}