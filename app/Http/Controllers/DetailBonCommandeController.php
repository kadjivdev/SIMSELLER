<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\BonCommande;
use Illuminate\Http\Request;
use App\Models\DetailBonCommande;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\detailCommandeProduitRule;
use App\Rules\detailCommandeRemiseRule;

class DetailBonCommandeController extends Controller
{

    
    public function index()
    {

       /* $commandes = $this->boncommandes->findOrFail($cde_id);
        $boncommandes = $commandes->produits()->get();
        $montants = null;
        $somme = 0;
        foreach($boncommandes as $boncommande){
            $montants = $boncommande->pivot->qteCommander * $boncommande->pivot->pu;
            $somme += $montants;

        }
        $commandes->montant = $somme;
		$commandes->update();*/

    }


    
    public function create()
    {
        //
    }


    
    public function store(Request $request, BonCommande $boncommande, DetailBonCommande $detailboncommandes = NULL)
    {

        try {

                if($detailboncommandes){
                    if($request->remise == NULL){
                        $validator = Validator::make($request->all(), [
                            'produit_id' => ['required'],
                            'qteCommander' => ['required'],
                            'pu' => ['required'],
                        ]);
                    }else{
                        $validator = Validator::make($request->all(), [
                            'produit_id' => ['required'],
                            'qteCommander' => ['required'],
                            'pu' => ['required'],
                            'remise' => ['required', new detailCommandeRemiseRule($request->qteCommander, $request->pu)],
                        ]);
                    }

                        if($validator->fails()){
                            return redirect()->route('boncommandes.edit', ['boncommande' => $boncommande->id])->withErrors($validator->errors())->withInput();
                        }


                        $detailboncommande = $detailboncommandes->update([
                            'bon_commande_id' => $boncommande->id,
                            'produit_id' => $request->produit_id,
                            'qteCommander' => $request->qteCommander,
                            'pu' => $request->pu,
                            'remise' => $request->remise,
                            'users' => Auth::user()->name,
                        ]);


                        if ($detailboncommande) {

                            $detailboncommandes = $detailboncommandes->where('bon_commande_id', $boncommande->id)->get();
                            $montants = null;
                            $somme = 0;
                            foreach($detailboncommandes as $detailboncommande){
                                $montants = ($detailboncommande->qteCommander * $detailboncommande->pu)-$detailboncommande->remise;
                                $somme += $montants;

                            }
                            $boncommande->montant = $somme;
                            $detailboncommande = $boncommande->update();

                            if($detailboncommande){
                                Session()->flash('message', 'Produit bon commande modifié succès!');
                                return redirect()->route('boncommandes.edit', ['boncommande' => $boncommande->id]);
                            }
                        }
                }else{
                        if($request->remise == NULL){
                            $validator = Validator::make($request->all(), [
                                'produit_id' => ['required', new detailCommandeProduitRule($boncommande->id)],
                                'qteCommander' => ['required'],
                                'pu' => ['required'],
                            ]);
                        }else{
                            $validator = Validator::make($request->all(), [
                                'produit_id' => ['required', new detailCommandeProduitRule($boncommande->id)],
                                'qteCommander' => ['required'],
                                'pu' => ['required'],
                                'remise' => ['required', new detailCommandeRemiseRule($request->qteCommander, $request->pu)],
                            ]);
                        }

                        if($validator->fails()){
                            return redirect()->route('boncommandes.edit', ['boncommande' => $boncommande->id])->withErrors($validator->errors())->withInput();
                        }

                        $detailboncommande = DetailBonCommande::create([
                            'bon_commande_id' => $boncommande->id,
                            'produit_id' => $request->produit_id,
                            'qteCommander' => $request->qteCommander,
                            'pu' => $request->pu,
                            'remise' => $request->remise,
                            'users' => Auth::user()->name,
                        ]);

                        if ($detailboncommande) {

                            $detailboncommandes = $detailboncommande->where('bon_commande_id', $boncommande->id)->get();
                            $montants = null;
                            $somme = 0;
                            foreach($detailboncommandes as $detailboncommande){
                                $montants = ($detailboncommande->qteCommander * $detailboncommande->pu)-$detailboncommande->remise;
                                $somme += $montants;

                            }
                            $boncommande->montant = $somme;
                            $detailboncommandes = $boncommande->update();

                            if($detailboncommandes){
                                Session()->flash('message', 'Produit bon commande modifié succès!');
                                return redirect()->route('boncommandes.edit', ['boncommande' => $boncommande->id]);
                            }
                        }

                }


        }catch (Exception $e){
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('boncommandes.index');
            }
        }

    }


    
    public function show(DetailBonCommande $detailBonCommande)
    {
        //
    }


    
    public function edit(DetailBonCommande $detailBonCommande)
    {
        //
    }


    
    public function update(Request $request, DetailBonCommande $detailBonCommande)
    {
        //
    }


    
    public function destroy(DetailBonCommande $detailBonCommande)
    {
        //
    }
}
