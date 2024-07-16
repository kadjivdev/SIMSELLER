<?php

namespace App\Http\Controllers\Api;

use App\Models\Camion;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use App\Models\Vendu;
use App\Models\Vente;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\Models\Programmation;
use App\Models\DetailBonCommande;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProgrammeController extends Controller
{
    public function getChauffeurByCamionId(Camion $camion)
    {
        return response($camion->chauffeur()->first());
    }


    public function getProgrammationByProduitId(Produit $produit, User $user)
    {
        $zones = $user->representant->zones->pluck('id')->toArray();
        $detailboncommandes = DetailBonCommande::where('produit_id', $produit->id)->pluck('id');
        $programmations = Programmation::where('statut', 'Livrer')->whereIn('detail_bon_commande_id', $detailboncommandes)->whereIn('zone_id', $zones)->with('camion')->get();
        $newProgrammation = [];
        foreach ($programmations as $programmation) {
            $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
            if ($qteVendu < $programmation->qtelivrer || (($programmation->qtelivrer < $programmation->qteprogrammer))) {
                $newProgrammation[] = $programmation;
            }
        }
        return response($newProgrammation);
    }

    public function getStockDisponible(Programmation $programmation)
    {
        $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
        $stockDispo = $programmation->qtelivrer - $qteVendu;
        return response($stockDispo);
    }

    public function getProgrammationById($programmation)
    {
        $programmation = Programmation::find($programmation);
        $zones = Zone::where('id', '<>', $programmation->zone_id)->get();
        return response(['programmation' => $programmation, 'zones' => $zones, 'zone_source' => $programmation->zone->libelle]);
    }

    ####_____REDIRECTION VERS LA PAGE DE TRANSFERT
    public function getProgrammationById_redirect(Request $request,$programmation)
    {
        $programmation = Programmation::find($programmation);
        $zones = Zone::where('id', '<>', $programmation->zone_id)->get();
        return view("livraisons.transfertCamion", compact('programmation', 'zones'));
    }

    public function getdetailTransfert($programmation)
    {
        $programmation = Programmation::find($programmation);
        $transferts = json_decode($programmation->transfert);
        $detailTransferts = [];
        foreach ($transferts as $transfert) {
            $source = Zone::find($transfert->source);
            $destination = Zone::find($transfert->destination);
            $user = User::find($transfert->user);
            $detailTransferts[] = [
                'source' => $source->libelle,
                'destination' => $destination->libelle,
                'user' => $user->name,
                'date' => date_format(date_create($transfert->date), 'd/j/Y H:i'),
                'observation' => $transfert->observation,
                'compteur' => $transfert->compteur
            ];
        }
        return response($detailTransferts);
    }

    public function getProduitFournisseur(Fournisseur $fournisseur)
    {
        if ($fournisseur)
            return response($fournisseur->produits);
        else
            return response("Fournisseur non trouvé", 404);
    }

    public function insertDate(Request $request, Programmation $programmation)
    {
        if ($programmation->dateprogrammer > $request->dateSortie) {
            return response("Date sortie inférieur à la date programmation", 401);
        }
        if ($request->dateSortie > date('Y-m-d')) {
            return response("Date sortie supérieur à la date du jour.", 401);
        }
        $historiques = $programmation->historiques;
        if (count($historiques) > 0) {
            if (array_key_exists('dateSortie', $historiques)) {
                $sortie = $historiques['dateSortie'];
            }
        }
        $itemesortie['user'] = $request->userName;
        $itemesortie['date'] = $programmation->dateSortie;
        $itemesortie['update_at'] = date('d/m/y H:i');
        $sortie[] = $itemesortie;
        $historiques['dateSortie'] = $sortie;

        $update = $programmation->update([
            'dateSortie' => $request->dateSortie,
            'historiques' => json_encode($historiques)
        ]);
        if ($update) {
            return response('Date mise à jour', 200);
        } else {
            return response("La mise à jour à echouée. Merci de reprendre ou contacter l'admin", 401);
        }
    }

    public function bordLiv(Programmation $programmation, $bl, $user)
    {
        // return response("requete en cours ....");
        try {
            $rules = ['bl' => ['required', 'string', 'unique:programmations,bl_gest']];
            $data = ['bl' => $bl];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return response('Le Bordereau de Livraison existe déjà', 401);
            }

            $historiques = $programmation->historiques;
            if (count($historiques) > 0) {
                if (array_key_exists('BL_GEST', $historiques)) {
                    $sortie = $historiques['BL_GEST'];
                }
            }
            $itemesortie['user'] = $user;
            $itemesortie['date'] = $programmation->dateSortie;
            $itemesortie['update_at'] = date('d/m/y H:i');
            $sortie[] = $itemesortie;
            $historiques['dateSortie'] = $sortie;

            $update = $programmation->update([
                'bl_gest' => $bl,
                'historiques' => json_encode($historiques)
            ]);
            if ($update) {
                return response('Bordereau de Livraison mise à jour', 200);
            } else {
                return response("La mise à jour à echouée. Merci de reprendre ou contacter l'admin", 401);
            }
        } catch (\Throwable $e) {

            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return  response()->json('Error');;
            }
        }
    }

    public function comptabilise(Request $request, Vente $vente)
    {
        $vente->update([
            'date_comptabilisation' => date('Y-m-d'),
            'taux_aib' => $request->taux_aib,
            'taux_tva' => $request->taux_tva,
            'prix_TTC' => $request->prix_TTC,
            'marge' => $request->marge,
            'comptabiliser' => 1
        ]);
        return response("Vente comptabilisé");
    }

    public function bordLivViaPost(Request $request, Programmation $programmation, $user)
    {

        ###___BON DE COMMANDE ASSOCIE
        // dd($programmation->detailboncommande->boncommande);
        // $bon_de_commande = $programmation->detailboncommande->boncommande;
        // return $bon_de_commande->statut=="Valider";


        $bl = $request->get('bl');
        $rules = ['bl' => ['required', 'string', 'unique:programmations,bl_gest']];
        $data = ['bl' => $bl];

        ##___VALIDATION
        Validator::make(
            $data,
            $rules,
            [
                'bl.required' => 'Le Bl est requis',
                'bl.string' => 'Le Bl doit être un string',
                'bl.unique' => 'Ce Bl existe déjà',
            ]
        )->validate();


        $historiques = $programmation->historiques;
        if (count($historiques) > 0) {
            if (array_key_exists('BL_GEST', $historiques)) {
                $sortie = $historiques['BL_GEST'];
            }
        }
        $itemesortie['user'] = $user;
        $itemesortie['date'] = $programmation->dateSortie;
        $itemesortie['update_at'] = date('d/m/y H:i');
        $sortie[] = $itemesortie;
        $historiques['dateSortie'] = $sortie;

        $update = $programmation->update([
            'bl_gest' => $bl,
            'historiques' => json_encode($historiques)
        ]);

        ###___
        // Session()->flash('message', 'Bordereau de Livraison mise à jour');

        if ($update) {
            return redirect('/livraisons/suivi-camion')->with('message', 'Bordereau de Livraison mise à jour');
        }
        return redirect('/livraisons/suivi-camion')->with('error', 'Oooops! Mise à jour échouée!');
    }
}
