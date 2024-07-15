<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Produit;
use App\Models\BonCommande;
use Illuminate\Http\Request;
use App\Models\DetailBonCommande;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDetailBonCommandeRequest;
use App\Http\Requests\UpdateDetailBonCommandeRequest;

class DetailBonCommandeController extends Controller
{
    protected $boncommandes, $produits;

    public function __construct(BonCommande $boncommandes, Produit $produits)
    {
        $this->boncommandes = $boncommandes;
        $this->produits = $produits;
    }


    public function index()
    {

    }



    public function create()
    {

    }



    public function store(StoreDetailBonCommandeRequest $request)
    {

        $produits = $request->produit_id;

        $boncommandes = $this->boncommandes->findOrFail($request->bon_commande_id);

        $detailboncommande = $boncommandes->produits()->attach([$produits => ['qteCommander' => $request->qteCommander, 'pu' => $request->pu] ]);

        if(!$detailboncommande){
            return response('Produit ajouté avec succès!');
        }
    }



    public function show()
    {
        //
    }


    public function edit($id)
    {
        $commercialisers = $this->commercialisers->findOrFail($id);

        return view('commercialisers.edit', compact('commercialisers'));

    }



    public function update(UpdateDetailBonCommandeRequest $request)
    {
        $produits = $request->produit_id;

        $boncommandes = $this->boncommandes->findOrFail($request->bon_commande_id);

        $detailboncommande = $boncommandes->produits()->sync([$produits => ['qteCommander' => $request->qteCommander, 'pu' => $request->pu, 'remise' => $request->remise, 'qteValider' => $request->qteValider, 'users' => $request->users]], false);

        if($detailboncommande){
            return response('Produit(s) modifié(s) avec succès!');
        }
    }


    public function delete($fournisseur_id, $produit_id)
    {
        $fournisseurs = $this->fournisseurs->findOrFail($fournisseur_id);
        $produits = $fournisseurs->produits()->findOrFail($produit_id);
        //dd($fournisseurs, $produits);
        return view('commercialisers.delete', compact('fournisseurs', 'produits'));

    }


    public function empty($bon_commande_id)
    {
        $boncommandes = $this->boncommandes->findOrFail($bon_commande_id);
        $detailboncommandes = $boncommandes->produits()->detach();
        if($detailboncommandes){
            if($boncommandes){
                return response(200);

            }
            else{
                return response(['error'=>'Echec: Une erreur est survenue lors de la suppression du produit.
            Veuiller réessayer et si l\'erreur persiste, contacter le concepteur'], 401);
            }
        }
    }

    public function destroy(DetailBonCommande $detailcommande)
    {
        try{
            $detailcommandes = $detailcommande->delete();
            if($detailcommandes){
                $getboncommandes = DetailBonCommande::where('bon_commande_id', $detailcommande->bon_commande_id)->get();
                $montants = null;
                $somme = 0;
                foreach($getboncommandes as $getboncommande){
                    $montants = ($getboncommande->qteCommander * $getboncommande->pu)-$getboncommande->remise;
                    $somme += $montants;
        
                }
                $boncommande = BonCommande::findOrFail($detailcommande->bon_commande_id);
                $boncommande->montant = $somme;
                $confirmation = $boncommande->update();

                if($confirmation){
                    return response('Produit supprimer avec succès!', 200);
                }
                
            }
        }catch (Exception $e){
            return response($e);
        }
    }

    public function  detailCommande($cde_id){
        $commandes = $this->boncommandes->findOrFail($cde_id);
        $boncommandes = $commandes->produits()->get();
        $montants = null;
        $somme = 0;
        foreach($boncommandes as $boncommande){
            $montants = $boncommande->pivot->qteCommander * $boncommande->pivot->pu;
            $somme += $montants;

        }
        $commandes->montant = $somme;
		$commande = $commandes->update();
		if($commande)
			return response(['BonCommande'=>$boncommandes,'somme'=>$somme]);
    }


    public function  listedetail(BonCommande $boncommande){
        $boncommandes = $boncommande->detailboncommandes()->get();

		return response(['BonCommande'=>$boncommandes]);
    }

}
