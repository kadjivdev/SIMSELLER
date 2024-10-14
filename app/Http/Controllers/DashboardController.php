<?php

namespace App\Http\Controllers;

use App\Models\BonCommande;
use App\Models\Client;
use App\Models\ClientOld;
use App\Models\CommandeClient;
use App\Models\Programmation;
use App\Models\User;
use App\Models\Vendu;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // QUAND C'EST NI UN ADMINISTRATEUR, NI UN CONTROLEUR ,NI UN VALIDATEUR,NI UN SUPERVISEUR
        if (!Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() &&  !Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists()  && !Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() && !Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()) {

            ###___CONTROLLEUR DE VENTE
            if (Auth::user()->roles()->where('libelle', ['CONTROLEUR VENTE'])->exists()) {
                return redirect()->route('ventes.venteAEnvoyerComptabiliser');
            }

            // QUAND C'EST UN GESTIONNAIRE, COMPTABLE ,VALIDATEUR OU CONTROLLEUR DE VENTE
            if (Auth::user()->roles()->where('libelle', ['GESTIONNAIRE'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists() || Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists()) {
                return redirect()->route("boncommandes.index");
            };

            // QUAND C'EST UN VENDEUR, SUPERVISEUR
            if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() || Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists()) {
                return redirect()->route("livraisons.index");
            }
        }

        #####___
        $boncommandesP = BonCommande::where('statut', 'Préparation')->count();
        $boncommandesV = BonCommande::where('statut', 'Valider')->count();
        $programmationsV = Programmation::where('statut', 'Valider')->count();
        $cdes = BonCommande::where('statut', 'Valider')->get();
        // $progs = Programmation::whereNotNull('imprimer')->count();
        $_progs = Programmation::where('statut', "Livrer")->get();
        $progs = $_progs->count();
        $livs = Programmation::whereNotNull('qtelivrer')->get();
        $sansRecu = 0;
        $nbrLiv = 0;
        $qteLiv = $_progs->sum("qtelivrer");

        foreach ($cdes as $cde) {
            if (!$cde->recus->first)
                $sansRecu++;
        }

        // foreach ($livs as $liv) {
        //     $qteVendu = Vendu::where('programmation_id', $liv->id)->sum('qteVendu');
        //     $stockDispo = $liv->qtelivrer - $qteVendu;
        //     if ($stockDispo > 0) {
        //         $qteLiv += $stockDispo;
        //         $nbrLiv++;
        //     }
        // }

        $produitNP = $progs; //DetailBonCommande::whereIn('bon_commande_id', $boncommandes)->whereNotIn('id', $programmations)->count();
        $now = Carbon::now();
        $vente = Vente::where('statut', 'Vendue')->whereBetween('date', [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')])->sum('montant');

        $cde = CommandeClient::where('statut', 'Valider')->whereBetween('dateBon', [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')])->count();
        $impayer = 0;
        $client = 0;
        $umpaid_vente = 0;
        $ventes = Vente::where('statut', 'Vendue')->where('type_vente_id', 2)->orderByDesc('code')->get();

        foreach ($ventes as $vte) {
            if (($vte->montant - $vte->reglements()->sum('montant')) != 0) {
                $umpaid_vente++;
                $impayer += $vte->montant - $vte->reglements()->sum('montant');
            }
        }

        return view('dashboard', compact('boncommandesP', 'boncommandesV', 'programmationsV', 'produitNP', 'sansRecu', 'nbrLiv', 'qteLiv', 'vente', 'cde', 'client', 'impayer', "umpaid_vente"));
    }
}
