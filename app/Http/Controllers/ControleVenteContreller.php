<?php

namespace App\Http\Controllers;

use App\Models\DetteReglement;
use App\Models\Reglement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControleVenteContreller extends Controller
{
    public function index(Request $request)
    {
        // REGLEMENTS PAS ENCORE VALIDES
        $users = User::all();
        $reglements = Reglement::whereNull("vente_id")->orderBy("statut", "asc")->get();

        #####____UN ADMINISTRATEUR & UN CONTROLLEUR PEUVENT VOIR TOUT LES REGLEMENTS, 
        #####____ MAIS UN VENDEUR NE PEUT QUE VOIR LES REGLEMENTS EN SON NOM
        if (Auth::user()->roles()->where('libelle', ['VENDEUR'])->exists() && !Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() && !Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists()) {
            $reglements = $reglements->where("user_id", Auth::user()->id);
        }

        if ($request->method() == "POST") {
            if ($request->user == "tout") {
                $reglements = $reglements->whereBetween("created_at", [$request->debut, $request->fin]);
            }

            if ($request->user != "tout") {
                $reglements = $reglements->whereBetween("created_at", [$request->debut, $request->fin])->where("user_id", $request->user);
            }

            $request->session()->flash("search", ["debut" => $request->debut, "fin" => $request->fin]);
        }

        ####____ POUR DJIBRIL 1 MR AIME, ON RECUPERE LES REGLEMENTS NON VALIDES
        $user = Auth::user();
        if (IS_DJIBRIL_ACCOUNT($user) || IS_AIME_ACCOUNT($user)) {
            $reglements = $reglements->where("statut", 0);
        }

        ####____
        return view('ctlventes.index', compact('reglements', 'users'));
    }

    public function reglementSurCompte()
    {
        $reglements = Reglement::where('type_detail_recu_id', NULL)->where('vente_id', '<>', NULL)->where('document', NULL)->get();
        return view('ctlventes.reglementSurCompte', compact('reglements'));
    }

    public function controler(Reglement $reglement)
    {
        return view('ctlventes.create', compact('reglement'));
    }

    public function validerApprovisionnement(Reglement $reglement)
    {
        // $vente = Vente::find($reglement->vente->id);
        $reglement->statut = 1;
        $reglement->observation_validation = 'RAS';
        $reglement->user_validateur_id = Auth::user()->id;

        // ajout du reglement au compte du client concerné
        $reglement->client_id = $reglement->clt;
        $reglement->update();

        // Mise à jour du mouvement attaché au reglement en question
        $compte = $reglement->client->compteClients->first();
        $mvt = $reglement->_mouvements->first();
        if ($mvt && $compte) {
            $mvt->compteClient_id = $compte->id;
            $mvt->update();
        }

        // Mise à jour compte client
        $client = $reglement->client;

        // pour un reglement de dette ancienne, on ne credite pas le compte du client
        if ($client->debit_old && $reglement->for_dette) {
            $client->credit = $client->credit + 0;
        } else {
            $client->credit = $client->credit + $reglement->montant;
        }

        #####===== REVERSEMENT DE L'ANCIEN SOLDE ======####
        if ($client->credit_old && $reglement->old_solde) {
            $client->credit_old = $client->credit_old - $reglement->montant;
        }

        $client->update();

        #####===== SI C'EST UN APPROVISIONNEMENT POUR REGLER UNE ANCIENNE DETTE ======####
        if ($client->debit_old && $reglement->for_dette) {
            $data = [
                'reference' => strtoupper($reglement->reference),
                'date' => $reglement->date,
                'montant' => $reglement->montant,
                'document' => $reglement,
                'compte' => $reglement->compte_id,
                'type_detail_recu' => $reglement->type_detail_recu_id,
                'operator' => auth()->user()->id,
                'client' => $client->id,
                'reglement_id' => $reglement->id,
            ];

            DetteReglement::create($data);

            ###___ACTUALISATION DU DEBIT_OLD DU CLIENT
            $client->debit_old = $client->debit_old + $reglement->montant;
            $client->save();
        }

        return redirect()->route('ctlventes.index')->with('message', 'Règlement validé avec succès');
    }

    public function rejetApprovisionnement(Request $request, Reglement $reglement)
    {
        $reglement->statut = null;
        $reglement->observation_validation = $request->observation;
        $reglement->user_validateur_id = Auth::user()->id;
        $reglement->update();

        $desMail = User::find($reglement->user_id);
        $copieMail = User::find(env('COPIE_GESTIONNAIRE_VENTE'));
        $message = "<p> Nous vous notifions que votre Réglement N° " . $reglement->code . "  a été rejeter par <b>" . Auth::user()->name . "</b>.
        <br> L'Observation du rejet est : <em style='color:red;'>" . $reglement->observation_validation . "</em>
        Merci de vous connecter pour effectuer le traitement.<br>
         </p>";
        return redirect()->route('ctlventes.index')->with('message', 'Règlement rejeté');;
    }

    function destroy(Request $request, Reglement $reglement)
    {
        if ($reglement) {
            foreach ($reglement->_mouvements as $mvt) {
                $mvt->delete();
            }
            $reglement->delete();
            return redirect()->route("ctlventes.index")->with("message", "Approvisionnement supprimé avec succès!");
        }
        return redirect()->route("ctlventes.index")->with("error", "Cet Approvisionnement n'existe pas!");
    }
}
