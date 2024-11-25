<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Compte;
use App\Models\CompteClient;
use App\Models\DetteReglement;
use App\Models\Mouvement;
use App\Models\Parametre;
use App\Models\Reglement;
use App\Models\TypeDetailRecu;
use App\Models\User;
use App\Models\Vente;
use App\Rules\ReglementMontantRule;
use App\tools\CompteTools;
use App\tools\ControlesTools;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompteClientController extends Controller
{

    public function show(Client $client)
    {
        $compteClient = $client->compteClients;
        $compteClient = count($compteClient) > 0 ? $compteClient[0] : CompteTools::addCompte($client->id, auth()->user()->id);

        // ON RECUPERE LES APPROVISIONNEMENTS DE CE CLIENT
        $reglements = $client->reglements->whereNull("vente_id");
        $mouvements = [];

        $reglement = Reglement::findOrFail(6919);
        
        // on recupere les mouvements associés à chaque approvisionnement
        foreach ($reglements as $reglement) {
            if (count($reglement->_mouvements)>0) {
                $mouvements[] = $reglement->_mouvements->first();
            }
        }
        $mouvements = collect($mouvements);

        // les approvisionnements rejetés
        $rejet_reglements = Reglement::where("clt", $client->id)->where("observation_validation", "!=", "RAS")->get();
        return view('compteClients.show', compact('compteClient', 'mouvements', 'client', 'rejet_reglements'));
    }

    public function createAppro(Request $request, Client $client)
    {
        $comptes = Compte::all();
        $typedetailrecus = TypeDetailRecu::all();
        return view('compteClients.appro', compact('client', 'comptes', 'typedetailrecus'));
    }

    public function postAppro(Request $request, Client $client)
    {
        ######____
        if (!$client->compteClients) {
            return back()->with("error", "Ce Client ne dispose pas de compte");
        }

        try {
            #########_____________________________________________##############
            ######### L'approvisionnement commence à partir d'ici ##############
            #########_____________________________________________##############

            ###___ ON DOIT CHOISIR SOIT UN REMBOURSEMENT OU UN REVERSEMENT
            if ($request->old_solde && $request->for_dette) {
                return back()->with("error", "Choisissez soit un remboursement de dette ou soit un reversement d'ancien solde")->withInput();
            }

            $validator = Validator::make($request->all(), [
                'reference' => ['required', 'string', 'max:255', 'unique:detail_recus'],
                'date' => ['required', 'before_or_equal:now'],
                'montant' => ['required'],
                'document' => ['required', 'file', 'mimes:pdf,docx,doc,jpg,jpeg'],
                'compte_id' => ['required'],
                'typedetailrecu_id' => ['required'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('compteClient.appro', ['client' => $client->id])->withErrors($validator->errors())->withInput();
            }

            #####===== VALIDATION EN CAS DE REGLEMENT DE DETTE ANCIENNE
            if ($request->for_dette) {
                $request->validate([
                    "reference" => ["required", "unique:dette_reglements,reference"],
                ]);
            }

            /* Uploader les documents dans la base de données */
            // $filename = time() . '.' . $request->document->extension();

            // $file = $request->file('document')->storeAs(
            //     'documents',
            //     $filename,
            //     'public'
            // );

            // TRAITEMENT DU DOCUMENT
            $doc = $request->file("document");
            $doc_name = $doc->getClientOriginalName();
            $doc->move("files/", $doc_name);

            $file = asset("files/" . $doc_name);

            $format = env('FORMAT_REGLEMENT');
            $parametre = Parametre::where('id', env('REGLEMENT'))->first();
            $code = $format . str_pad($parametre->valeur, 6, "0", STR_PAD_LEFT);

            ####____VERIFIONS SI CE REGLEMENT EXISTAIT DEJA
            $_rg_existe = Reglement::where("reference", strtoupper($request->reference))->first();
            if ($_rg_existe) {
                return back()->with("error", "Cette reference existe déjà");
            }

            // on ajoute pas directement l'approvisionnement sur le compte du client
            // on l'ajoute d'abord au *clt* (qui n'est rien d'autre que le client mais masqué )
            // c'est après validation de l'approvisionnement par le contrôlleur que c'est ajouté au compte du client

            $reglement = Reglement::create([
                'code' => $code,
                'reference' => strtoupper($request->reference),
                'date' => $request->date,
                'montant' => $request->montant,
                'document' => $file,
                'vente_id' => null,
                'compte_id' => $request->compte_id,
                'type_detail_recu_id' => $request->typedetailrecu_id,
                'user_id' => auth()->user()->id,
                // 'client_id' => $client->id,
                'clt' => $client->id,
                'for_dette' => $request->for_dette ? true : false,
                'old_solde' => $request->old_solde ? true : false,
            ]);

            if ($reglement) {

                $valeur = $parametre->valeur;

                $valeur = $valeur + 1;

                $parametres = Parametre::find(env('REGLEMENT'));

                $parametre = $parametres->update([
                    'valeur' => $valeur,
                ]);

                // Ici aussi on fait la même chose. On ajoute pas directement
                // le mouvement au compte du client
                //  on le fait plutôt après validation de l'approvisionnement associé à ce mouvement

                if ($parametre) {
                    $mouvement = Mouvement::create([
                        'libelleMvt' => $request->libelleMvt,
                        'dateMvt' => $request->date,
                        'montantMvt' => $request->montant,
                        // 'compteClient_id' => $client->compteClients[0]->id,
                        'compteClient_id' => null,
                        'reglement_id' => $reglement->id,
                        'sens' => 0,
                        'user_id' => auth()->user()->id
                    ]);

                    #####______
                    Session()->flash('message', 'Compte approvisionné avec succès, mais en attente de validation');
                    return redirect()->route('compteClient.show', ['client' => $client->id]);
                }
            }
        } catch (\Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e->getMessage();
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('compteClient.show', ['client' => $client->id]);
            }
        }
    }

    public function _updateAppro(Request $request)
    {
        $appro = Reglement::findOrFail($request->approId);

        ###___CONTROLE SUR LA REFEREENCE AU CAS OU L'AGENT ENTRE UNE REFERENCE DIFFERENTE DE CELLE 
        #####  DU REGLEMENT EN MODIFICATION
        if ($appro->reference != $request->reference) {
            $reglement_existe = Reglement::where("reference", $request->reference)->first();
            if ($reglement_existe) {
                return back()->with("error", "Cette reference existe déjà");
            }
        }

        if ($request->document) {
            /* Uploader les documents dans la base de données */
            $filename = time() . '.' . $request->document->extension();

            $file = $request->file('document')->storeAs(
                'documents',
                $filename,
                'public'
            );
        }

        $data = [
            "reference" => $request->reference,
            "date" => $request->date,
            "montant" => $request->montant,
            "document" => $request->document ? $file : $appro->document,
            'for_dette' => $request->for_dette ? true : false,
            'old_solde' => $request->old_solde ? true : false,
            'observation_validation' => null
        ];

        // update de l'approvisionnement
        $appro->update($data);

        // update du mouvement attaché à cet approvisionnement
        if (count($appro->_mouvements)>0) {
            $appro->_mouvements[0]->montantMvt = $request->montant;
            $appro->_mouvements[0]->update();
        }

        return back()->with("message", "Approvisionnement ($request->reference) modifié avec succès! Attendez sa validation.");
    }

    public function delete(Mouvement $mouvement, Client $client)
    {
        return view('compteClients.delete', compact('mouvement', 'client'));
    }

    public function destroy(Mouvement $mouvement, Client $client)
    {
        if ($mouvement->compteClient_id) {
            $mouvementnew = Mouvement::create([
                'libelleMvt' => "suppression approvisionnement",
                'dateMvt' => Carbon::now(),
                'montantMvt' => $mouvement->montantMvt,
                'compteClient_id' => $mouvement->compteClient_id,
                'user_id' => auth()->user()->id,
                'sens' => 2,
                'reglement_id' => $mouvement->reglement_id,
                'destroy' => true
            ]);

            $mouvement->destroy = true;
            $mouvement->update();

            $reglement = Reglement::find($mouvement->reglement_id);

            ControlesTools::generateLog($reglement, 'reglement', 'Suppression règlement');

            // on diminue le credit du client seulement quand le reglement n'a pas été fait 
            // pour regler une ancienne dette(parce que dans ce cas, le montant n'avait pas été ajouté au credit du client)
            if (!$reglement->for_dette) {
                $client->credit = $client->credit - $mouvement->montantMvt;
            } else {
                // on ramène la dette à sa place
                $client->debit_old = $client->debit_old - $mouvement->montantMvt;
            }

            ###____
            $client->update();

            if ($reglement->for_dette) {
                ###____suppression du reglement de dette attaché
                $reglement->_DetteReglement->delete();
            }
            ###____
            $reglement->delete();
        }

        if ($mouvement) {
            Session()->flash('message', 'Approvisionnement Modifier  avec succès');
            return redirect()->route('compteClient.show', ['client' => $client->id]);
        }
    }
}
