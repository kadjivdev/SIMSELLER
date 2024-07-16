<?php

namespace App\Http\Controllers;

use App\Mail\NotificationRejetReglement;
use App\Models\Reglement;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail;

class ControleVenteContreller extends Controller
{
    public function index(){
        $reglements = Reglement::where('statut',2)->where('document','<>',NULL)->get();
        // dd($reglements[0]);
        return view('ctlventes.index',compact('reglements'));
    }

    public function reglementSurCompte(){
        // dd("gogo");
        $reglements = Reglement::where('type_detail_recu_id',NULL)->where('vente_id','<>',NULL)->where('document',NULL)->get();
        return view('ctlventes.reglementSurCompte',compact('reglements'));
    }
    
    public function controler(Reglement $reglement){
        // dd($reglement);
        return view('ctlventes.create',compact('reglement'));
    }

    public function validerControle(Reglement $reglement){           

        $vente = Vente::find($reglement->vente->id);
        $reglement->statut = 1;
        $reglement->observation_validation = 'RAS';
        $reglement->user_validateur_id = Auth::user()->id;
        $reglement->update();

        // Mise à jour compte client
       
        $client = $vente->commandeclient->client;
        $client->debit = $client->debit + $reglement->montant;      
        $client->update();

        if($reglement->vente->montant == $vente->reglements->sum('montant')){
            $vente->statut = "Contrôller";
            $vente->update(); 
        }
        /* 
            $vente = Vente::find($reglement->vente_id);
            $vente->statut = 'controller'; 
        */
        return redirect()->route('ctlventes.index')->with('message', 'Règlement valider avec succès');
    }
    
    public function rejetReglement(Request $request, Reglement $reglement){

        $vente = Vente::find($reglement->vente->id);
        $vente->statut_reglement=0;
        $vente->update();

        $reglement->statut = null;
        $reglement->observation_validation = $request->observation;
        $reglement->user_validateur_id = Auth::user()->id;
        $reglement->update();
        
        $desMail = User::find($vente->users);
        $copieMail = User::find(env('COPIE_GESTIONNAIRE_VENTE'));
        $message = "<p> Nous vous notifions que votre Réglement N° ".$reglement->code."  a été rejeter par <b>".Auth::user()->name."</b>.
        <br> L'Observation du rejet est : <em style='color:red;'>".$reglement->observation_validation."</em>
        Merci de vous connecter pour effectuer le traitement.<br>
        
      
         </p>";
        $mail = new NotificationRejetReglement(['email'=>$desMail->email,'nom'=>$desMail->name],'Reglement n° '.$reglement->code.' du '.date_format(date_create($reglement->date),'d/m/Y'),$message,$vente,[$copieMail->email,env('GESTIONNAIRE_DIRECTION')]);
        Mail::send($mail);
        return redirect()->route('ctlventes.index')->with('message', 'Règlement rejeté');
        ;

    }
}
