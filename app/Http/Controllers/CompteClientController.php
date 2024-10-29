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
    public function __construct()
    {
        /*   $possedeAuMoinsUnDroit = User::where('users.id',Auth::user()->id)->join('avoirs', 'users.id','=','avoirs.user_id')
        ->join('roles', 'roles.id','=','avoirs.role_id')->whereIn('libelle', ['RECOUVREUR', 'SUPERVISEUR'])->exists();
    
        if (!$possedeAuMoinsUnDroit) {
            $this->middleware(['recouvreur', 'superviseur'])->except('show');
        } */
    }

    public function show(Client $client)
    {
        $compteClient = $client->compteClients;
        $compteClient = count($compteClient) > 0 ? $compteClient[0] : CompteTools::addCompte($client->id, auth()->user()->id);
        $mouvements = Mouvement::where('compteClient_id', $compteClient->id)->orderBy("id", "desc")->get();
        // $mouvements = collect($mouvements)->sortByDesc('id')->all();
        return view('compteClients.show', compact('compteClient', 'mouvements', 'client'));
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
            $filename = time() . '.' . $request->document->extension();

            $file = $request->file('document')->storeAs(
                'documents',
                $filename,
                'public'
            );

            $format = env('FORMAT_REGLEMENT');
            $parametre = Parametre::where('id', env('REGLEMENT'))->first();
            $code = $format . str_pad($parametre->valeur, 6, "0", STR_PAD_LEFT);

            ####____VERIFIONS SI CE REGLEMENT EXISTAIT DEJA
            $_rg_existe = Reglement::where("reference", strtoupper($request->reference))->first();
            if ($_rg_existe) {
                return back()->with("error", "Cette reference existe déjà");
            }

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
                'client_id' => $client->id,
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

                if ($parametre) {
                    $mouvement = Mouvement::create([
                        'libelleMvt' => $request->libelleMvt,
                        'dateMvt' => $request->date,
                        'montantMvt' => $request->montant,
                        'compteClient_id' => $client->compteClients[0]->id,
                        'reglement_id' => $reglement->id,
                        'sens' => 0,
                        'user_id' => auth()->user()->id
                    ]);
                    if ($mouvement) {
                        $compte = $mouvement->compteClient;
                        /* $compte->solde = $compte->solde + $request->montant;
                                $compte->update(); */

                        $client = $compte->client;
                        $client->credit = $client->credit + $request->montant;
                        $client->update();
                    }

                    #####===== REVERSEMENT DE L'ANCIEN SOLDE ======####
                    if ($request->old_solde) {
                        $client->credit_old = $client->credit_old - $request->montant;
                        $client->save();
                    }


                    #####===== REGLEMENT ANCIENNE DETTE ======####
                    if ($request->for_dette) {
                        $data = [
                            'reference' => strtoupper($request->reference),
                            'date' => $request->date,
                            'montant' => $request->montant,
                            'document' => $file,
                            'compte' => $request->compte_id,
                            'type_detail_recu' => $request->typedetailrecu_id,
                            'operator' => auth()->user()->id,
                            'client' => $client->id,
                        ];

                        DetteReglement::create($data);

                        ###___ACTUALISATION DU DEBIT DU CLIENT
                        $client->debit_old = $client->debit_old + $request->montant;
                        $client->save();
                    }

                    #####______
                    Session()->flash('message', 'Compte approvisionné avec succès');
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

            $compteClient = CompteClient::find($mouvement->compteClient_id);
            $Client = $compteClient->client;
            $mouvement->destroy = true;
            $mouvement->update();

            $reglement = Reglement::find($mouvement->reglement_id);
            ControlesTools::generateLog($reglement, 'reglement', 'Suppression règlement');

            $reglement->delete();

            if ($mouvementnew) {
                $client->debit = $client->debit + $mouvement->montantMvt;
                $client->update();

                // $compte = $mouvementnew->compteClient;
                // $compte->solde = $client->credit + $client->debit;
                // $compte->update();
            }
        }

        if ($mouvement) {
            Session()->flash('message', 'Approvisionnement Modifier  avec succès');
            return redirect()->route('compteClient.show', ['client' => $client->id]);
        }
    }
}
