<?php

namespace App\Http\Controllers;

use App\Models\BonCommande;
use App\Models\CommandeClient;
use App\Models\Programmation;
use App\Models\Vendu;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        ###___VALIDER TOUTES LES VENTES
        // foreach (Vente::all() as $vente) {
        //     $vente->valide=true;
        //     $vente->save();
        // }
        // QUAND C'EST NI UN ADMINISTRATEUR, NI UN CONTROLEUR ,NI UN VALIDATEUR,NI UN SUPERVISEUR
        if (!Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() && !Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() && !Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() && !Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()) {
            // QUAND C'EST UN GESTIONNAIRE, COMPTABLE ,VALIDATEUR
            if (Auth::user()->roles()->where('libelle', ['GESTIONNAIRE'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists() || Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists()) {
                return redirect()->route("boncommandes.index");
            };

            // QUAND C'EST UN VENDEUR, SUPERVISEUR
            if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() || Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists()) {
                return redirect()->route("livraisons.index");
            }
        }


        $boncommandesP = BonCommande::where('statut', 'Préparation')->count();
        $boncommandesV = BonCommande::where('statut', 'Valider')->count();
        $programmationsV = Programmation::where('statut', 'Valider')->count();
        $cdes = BonCommande::where('statut', 'Valider')->get();
        $progs = Programmation::whereNotNull('imprimer')->count();
        $livs = Programmation::whereNotNull('qtelivrer')->get();
        $sansRecu = 0;
        $nbrLiv = 0;
        $qteLiv = 0;
        foreach ($cdes as $cde) {
            if (!$cde->recu)
                $sansRecu++;
        }
        foreach ($livs as $liv) {
            $qteVendu = Vendu::where('programmation_id', $liv->id)->sum('qteVendu');
            $stockDispo = $liv->qtelivrer - $qteVendu;
            if ($stockDispo > 0) {
                $qteLiv += $stockDispo;
                $nbrLiv++;
            }
        }
        //Produit bon commande non programmé
        //$boncommandes = BonCommande::where('statut', 'Valider')->pluck('id');
        $produitNP = $progs; //DetailBonCommande::whereIn('bon_commande_id', $boncommandes)->whereNotIn('id', $programmations)->count();
        $now = Carbon::now();
        $vente = Vente::where('statut', 'Vendue')->whereBetween('date', [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')])->sum('montant');
        $cde = CommandeClient::where('statut', 'Valider')->whereBetween('dateBon', [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')])->count();
        $impayer = 0;
        $client = 0;
        $ventes = Vente::where('statut', 'Vendue')->where('type_vente_id', 2)->orderByDesc('code')->get();
        foreach ($ventes as $vte) {
            if (($vte->montant - $vte->reglements()->sum('montant')) <> 0) {
                $client++;
                $impayer += $vte->montant - $vte->reglements()->sum('montant');
            }
        }

        return view('dashboard', compact('boncommandesP', 'boncommandesV', 'programmationsV', 'produitNP', 'sansRecu', 'nbrLiv', 'qteLiv', 'vente', 'cde', 'client', 'impayer'));

        // if (!in_array('ADMINISTRATEUR', array_column(auth()->user()->roles->toArray(), 'libelle')) || !in_array('SUPERVISEUR', array_column(auth()->user()->roles->toArray(), 'libelle')))
        //     return view('representant', compact('boncommandesP', 'boncommandesV', 'programmationsV', 'produitNP', 'sansRecu', 'nbrLiv', 'qteLiv', 'vente', 'cde', 'client', 'impayer'));
        // else
        //     return view('dashboard', compact('boncommandesP', 'boncommandesV', 'programmationsV', 'produitNP', 'sansRecu', 'nbrLiv', 'qteLiv', 'vente', 'cde', 'client', 'impayer'));
    }
}
