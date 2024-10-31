<?php

namespace App\Http\Controllers;

use App\Mail\NotificationRejetReglement;
use App\Models\DetteReglement;
use App\Models\Reglement;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControleVenteContreller extends Controller
{
    public function index(Request $request)
    {
        // REGLEMENTS PAS ENCORE VALIDES
        $users = User::all();
        $reglements = Reglement::whereNull("vente_id")->orderBy("id","desc")->get();

        // dd($reglements);
        if ($request->method() == "POST") {
            if ($request->user == "tout") {
                $reglements = $reglements->whereBetween("created_at", [$request->debut, $request->fin]);
            }

            if ($request->user != "tout") {
                $reglements = $reglements->whereBetween("created_at", [$request->debut, $request->fin])->where("user_id", $request->user);
            }

            $request->session()->flash("search", ["debut" => $request->debut, "fin" => $request->fin]);
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

        #####===== APPROVISIONNEMENT POUR REGLER UNE ANCIENNE DETTE ======####
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

            ###___ACTUALISATION DU DEBIT DU CLIENT
            $client->debit_old = $client->debit_old + $reglement->montant;
            $client->save();
        }

        return redirect()->route('ctlventes.index')->with('message', 'Règlement validé avec succès');
    }

    public function rejetApprovisionnement(Request $request, Reglement $reglement)
    {
        // $vente = Vente::find($reglement->vente->id);
        // $vente->statut_reglement = 0;
        // $vente->update();

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
        // $mail = new NotificationRejetReglement(['email'=>$desMail->email,'nom'=>$desMail->name],'Reglement n° '.$reglement->code.' du '.date_format(date_create($reglement->date),'d/m/Y'),$message,$vente,[$copieMail->email,env('GESTIONNAIRE_DIRECTION')]);
        // Mail::send($mail);
        return redirect()->route('ctlventes.index')->with('message', 'Règlement rejeté');;
    }




    // =========== old
    public function _validerControle(Reglement $reglement)
    {
        $vente = Vente::find($reglement->vente->id);
        $reglement->statut = 1;
        $reglement->observation_validation = 'RAS';
        $reglement->user_validateur_id = Auth::user()->id;

        // ajout du reglement au compte du client concerné
        $reglement->client_id = $reglement->clt;
        $reglement->update();

        // Mise à jour du mouvement attaché au reglement en question
        $compte = $vente->commandeclient->client->compteClients->first();
        $mvt = $reglement->_mouvements->first();
        if ($mvt && $compte) {
            $mvt->compteClient_id = $compte->id;
            $mvt->update();
        }

        // Mise à jour compte client
        $client = $vente->commandeclient->client;

        // pour un reglement de dette ancienne, on ne credite pas le compte du client
        if ($reglement->for_dette) {
            $client->credit = $client->credit + 0;
        } else {
            $client->credit = $client->credit + $reglement->montant;
        }

        #####===== REVERSEMENT DE L'ANCIEN SOLDE ======####
        if ($reglement->old_solde) {
            $client->credit_old = $client->credit_old - $reglement->montant;
        }

        $client->update();

        #####===== APPROVISIONNEMENT POUR REGLER UNE ANCIENNE DETTE ======####
        if ($reglement->for_dette) {
            $data = [
                'reference' => strtoupper($reglement->reference),
                'date' => $reglement->date,
                'montant' => $reglement->montant,
                'document' => $reglement,
                'compte' => $reglement->compte_id,
                'type_detail_recu' => $reglement->typedetailrecu_id,
                'operator' => auth()->user()->id,
                'client' => $client->id,
            ];

            DetteReglement::create($data);

            ###___ACTUALISATION DU DEBIT DU CLIENT
            $client->debit_old = $client->debit_old + $reglement->montant;
            $client->save();
        }


        if ($reglement->vente->montant == $vente->reglements->sum('montant')) {
            $vente->statut = "Contrôller";
            $vente->update();
        }

        return redirect()->route('ctlventes.index')->with('message', 'Règlement validé avec succès');
    }

    public function _rejetReglement(Request $request, Reglement $reglement)
    {

        $vente = Vente::find($reglement->vente->id);
        $vente->statut_reglement = 0;
        $vente->update();

        $reglement->statut = null;
        $reglement->observation_validation = $request->observation;
        $reglement->user_validateur_id = Auth::user()->id;
        $reglement->update();

        $desMail = User::find($vente->users);
        $copieMail = User::find(env('COPIE_GESTIONNAIRE_VENTE'));
        $message = "<p> Nous vous notifions que votre Réglement N° " . $reglement->code . "  a été rejeter par <b>" . Auth::user()->name . "</b>.
        <br> L'Observation du rejet est : <em style='color:red;'>" . $reglement->observation_validation . "</em>
        Merci de vous connecter pour effectuer le traitement.<br>
        
      
         </p>";
        // $mail = new NotificationRejetReglement(['email'=>$desMail->email,'nom'=>$desMail->name],'Reglement n° '.$reglement->code.' du '.date_format(date_create($reglement->date),'d/m/Y'),$message,$vente,[$copieMail->email,env('GESTIONNAIRE_DIRECTION')]);
        // Mail::send($mail);
        return redirect()->route('ctlventes.index')->with('message', 'Règlement rejeté');;
    }
}
