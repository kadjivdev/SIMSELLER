<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vente;
use App\tools\CompteTools;
use Illuminate\Http\Request;

class ReglementController extends Controller
{
    public function getSoldeCompte(Vente $vente, User $user){
        $cdeClient = $vente->commandeclient;
        $client = $cdeClient->client;
        $compte = $client->compteClient;
        $compte = $compte ? $compte: CompteTools::addCompte($client->id, $user->id);
        return response($compte);
    }
}
