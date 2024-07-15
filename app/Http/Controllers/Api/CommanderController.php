<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Commander;
use Illuminate\Http\Request;
use App\Models\CommandeClient;
use App\Http\Controllers\Controller;

class CommanderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commander  $commander
     * @return \Illuminate\Http\Response
     */
    public function show(Commander $commander)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commander  $commander
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commander $commander)
    {
        //
    }



    public function destroy(Commander $commander)
    {
        try{
            $commanders = $commander->delete();
            if($commanders){
                $getcommandeclients = Commander::where('commande_client__id', $commander->commande_client_id)->get();
                $montants = null;
                $somme = 0;
                foreach($getcommandeclients as $getcommandeclient){
                    $montants = ($getcommandeclient->qteCommander * $getcommandeclient->pu)-$getcommandeclient->remise;
                    $somme += $montants;

                }
                $commandeclient = CommandeClient::findOrFail($commander->commande_client_id);
                $commandeclient->montant = $somme;
                $confirmation = $commandeclient->update();

                if($confirmation){
                    return response('Produit supprimer avec succès!', 200);
                }

            }
        }catch (Exception $e){
            return response($e);
        }
    }
}
