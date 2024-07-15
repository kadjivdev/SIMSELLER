<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Commander;
use Illuminate\Http\Request;
use App\Models\CommandeClient;
use Illuminate\Support\Facades\Auth;
use App\Rules\detailCommandeRemiseRule;
use Illuminate\Support\Facades\Validator;
use App\Rules\ProduitCommanderRule;
use App\tools\ControlesTools;

class CommanderController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    public function store(Request $request, CommandeClient $commandeclient, Commander $commander = NULL)
    {
        try {

            if($commander){
                if($request->remise == NULL){
                    $validator = Validator::make($request->all(), [
                        'produit_id' => ['required', new ProduitCommanderRule($commandeclient->id)],
                        'qteCommander' => ['required'],
                        'pu' => ['required'],
                    ]);
                }else{
                    $validator = Validator::make($request->all(), [
                        'produit_id' => ['required', new ProduitCommanderRule($commandeclient->id)],
                        'qteCommander' => ['required'],
                        'pu' => ['required'],
                        'remise' => ['required', new detailCommandeRemiseRule($request->qteCommander, $request->pu)],
                    ]);
                }

                    if($validator->fails()){
                        return redirect()->route('commandeclients.edit', ['commandeclient' => $commandeclient->id])->withErrors($validator->errors())->withInput();
                    }


                    $commanders = $commander->update([
                        'commande_client_id' => $commandeclient->id,
                        'produit_id' => $request->produit_id,
                        'qteCommander' => $request->qteCommander,
                        'pu' => $request->pu,
                        'remise' => $request->remise,
                        'users' => Auth::user()->id,
                    ]);
                    if ($commanders) {
                        $commanders = $commander->where('commande_client_id', $commandeclient->id)->get();
                        $montants = null;
                        $somme = 0;
                        foreach($commanders as $commander){
                            $montants = ($commander->qteCommander * $commander->pu)-$commander->remise;
                            $somme += $montants;

                        }
                        $commandeclient->montant = $somme;
                        $commandeclients = $commandeclient->update();

                        if($commandeclients){
                            Session()->flash('message', 'Produit commande client modifié succès!');
                            return redirect()->route('commandeclients.edit', ['commandeclient' => $commandeclient->id]);
                        }
                    }
            }else{

                    if($request->remise == NULL){
                        $validator = Validator::make($request->all(), [
                            'produit_id' => ['required', new ProduitCommanderRule($commandeclient->id)],
                            'qteCommander' => ['required'],
                            'pu' => ['required'],
                        ]);
                    }else{
                        $validator = Validator::make($request->all(), [
                            'produit_id' => ['required', new ProduitCommanderRule($commandeclient->id)],
                            'qteCommander' => ['required'],
                            'pu' => ['required'],
                            'remise' => ['required', new detailCommandeRemiseRule($request->qteCommander, $request->pu)],
                        ]);
                    }

                    if($validator->fails()){
                        return redirect()->route('commandeclients.edit', ['commandeclient' => $commandeclient->id])->withErrors($validator->errors())->withInput();
                    }

                    $commander = Commander::create([
                        'commande_client_id' => $commandeclient->id,
                        'produit_id' => $request->produit_id,
                        'qteCommander' => $request->qteCommander,
                        'pu' => $request->pu,
                        'remise' => $request->remise,
                        'users' => Auth::user()->id,
                    ]);

                    if ($commander) {

                        $commanders = $commander->where('commande_client_id', $commandeclient->id)->get();
                        $montants = null;
                        $somme = 0;
                        foreach($commanders as $commander){
                            $montants = ($commander->qteCommander * $commander->pu)-$commander->remise;
                            $somme += $montants;

                        }
                        $commandeclient->montant = $somme;
                        $commandeclients = $commandeclient->update();

                        if($commandeclients){
                            Session()->flash('message', 'Produit commande client modifié succès!');
                            return redirect()->route('commandeclients.edit', ['commandeclient' => $commandeclient->id]);
                        }
                    }
            }


    }catch (Exception $e){
        if(env('APP_DEBUG') == TRUE){
            return $e;
        }else{
            Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
            return redirect()->route('commandeclients.index');
        }
    }
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commander  $commander
     * @return \Illuminate\Http\Response
     */
    public function edit(Commander $commander)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commander  $commander
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy( $commander)
    {
        ControlesTools::generateLog($commander,'commander','Suppression ligne');
        $commander = Commander::find($commander);
        $cde = $commander->commandeclient;
        $commander->commandeclient()->update(['montant' => ($cde->montant - (($commander->qteCommander * $commander->pu)-($commander->remise ?:0)))]);
        $commander->delete();
        return redirect()->route('commandeclients.edit', ['commandeclient'=>$cde->id])->with('message','Suppression effectuée.');
    }
}
