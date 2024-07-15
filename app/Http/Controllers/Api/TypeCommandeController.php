<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\CommandeClient;
use App\Models\TypeCommande;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeCommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }



    public function getTypeCommandeByClientId(TypeCommande $typecommande,$client=null, $commandeClient=null)
    {
        if($commandeClient != 'undefined'){
            $commandeClient = CommandeClient::find($commandeClient);
            $client = $commandeClient->client;
        }
        else{
            $client = Client::find($client);
        }
        if($client->statutCredit == 0){
            $typecommandes = $typecommande->where('libelle', 'COMPTANTS')->get();
            return response($typecommandes);
        }elseif($client->statutCredit == 1){
            $typecommandes = $typecommande->get();
            return response($typecommandes);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeCommande  $typeCommande
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeCommande $typeCommande)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeCommande  $typeCommande
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeCommande $typeCommande)
    {
        //
    }
}
