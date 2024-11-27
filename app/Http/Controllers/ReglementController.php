<?php

namespace App\Http\Controllers;

use App\Jobs\ReglementUploadJob;
use App\Mail\NotificationValidationReglement;
use App\Mail\SuspectMail;
use App\Models\Client;
use App\Models\Compte;
use App\Models\CompteClient;
use App\Models\DetailRecu;
use App\Models\Mouvement;
use App\Models\Parametre;
use App\Models\Recu;
use App\Models\Reglement;
use App\Models\TypeDetailRecu;
use App\Models\User;
use App\Models\Vente;
use App\Rules\ActionMontantDetailRecuRule;
use App\Rules\ReglementMontantRule;
use App\tools\ControlesTools;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Null_;

class ReglementController extends Controller
{
    public function __construct()
    {
        $this->middleware('vendeur')->only(['create', 'store', 'update', 'delete']);
    }

    public function index(Vente $vente)
    {
        return view('reglements.index', compact('vente'));
    }

    public function create(Vente $vente)
    {
        $comptes = Compte::all();
        $typedetailrecus = TypeDetailRecu::all();
        return view('reglements.create', compact('vente', 'comptes', 'typedetailrecus'));
    }

    public function store(Request $request, Vente $vente)
    {
        try {
            $reglmt = $vente->reglements()->pluck('id');

            count($reglmt) == 0 ? $reglmt = NULL : $reglmt = Reglement::find($reglmt[0]);

            ####____ VERIFIONS SI CE CLIENT AVAIT UNE ANCIENNE DETTE
            if ($vente->commandeclient->client->debit_old) {
                Session()->flash('error', "Veuillez d'abord regler l'ancienne dette de ce client");
                return redirect()->route('reglements.index', ['vente' => $vente->id]);
            }

            ####____ VERIFIONS SI CETTE VENTE APPARTIENT AU USER CONNECTE
            if (auth()->user()->id != $vente->users) {
                Session()->flash('error', 'Cette vente ne vous appartient pas!');
                return redirect()->route('reglements.index', ['vente' => $vente->id]);
            }

            ####_____
            // if ($vente->commandeclient->client->compteClients->toArray()) {

            $cli = Client::findOrFail($vente->commandeclient->client->id);
            $credit = $cli->reglements->where("for_dette",false)->whereNull("vente_id")->sum("montant");
            $debit = $cli->reglements->whereNotNull("vente_id")->sum("montant");

            $clientSolde = $credit - $debit;

            if ($clientSolde < $request->montant) {
                Session()->flash('error', 'Le solde du client est insuffisant!');
                return redirect()->route('reglements.index', ['vente' => $vente->id]);
            }
            // }

            if ($vente->commandeclient->client->compteClients->toArray() == null) {
                Session()->flash('error', 'Ce client n\'a pas de compte actif');
                return redirect()->route('reglements.index', ['vente' => $vente->id]);
            }

            $validator = Validator::make($request->all(), [
                'date' => ['required', 'before_or_equal:now'],
                //   'document' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg'],
                'montant' => ['required', new ReglementMontantRule($vente, $request->srcReg, $reglmt)],
            ]);

            if ($validator->fails()) {
                return redirect()->route('reglements.create', ['vente' => $vente->id])->withErrors($validator->errors())->withInput();
            }

            $compte = $vente->commandeclient->client->compteClients->toArray()[0];
            $format = env('FORMAT_REGLEMENT');
            $parametre = Parametre::where('id', env('REGLEMENT'))->first();
            $code = $format . str_pad($parametre->valeur, 6, "0", STR_PAD_LEFT);

            $reglement = Reglement::create([
                'code' => $code,
                'reference' => "REGLEMENT SUR COMPTE CLIENT :" . $vente->commandeclient->client->raisonSociale,
                'date' => $request->date,
                'montant' => $request->montant,
                'vente_id' => $vente->id,
                'compte_id' => $compte['id'],
                'type_detail_recu_id' => null,
                'user_id' => auth()->user()->id,
                'client_id' => $vente->commandeclient->client->id,
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
                //  on le fait plutôt après validation du reglement associé à ce mouvement

                $mouvement = Mouvement::create([
                    'libelleMvt' => "Règlement d'achat de ciment",
                    'dateMvt' => $request->date,
                    'montantMvt' => $request->montant,
                    'compteClient_id' => $compte['id'],
                    'user_id' => auth()->user()->id,
                    'sens' => 0,
                    'reglement_id' => $reglement->id,
                    // 'destroy' => true
                ]);

                if ($mouvement) {
                    $compte = $mouvement->compteClient;

                    $client = $compte->client;
                    $client->debit = $client->debit + $request->montant;
                    $client->update();
                }

                Session()->flash('message', 'Règlement effectué avec succès');
                return redirect()->route('reglements.index', ['vente' => $vente->id]);
            }
        } catch (\Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('reglements.index', ['vente' => $vente->id]);
            }
        }
    }

    public function show(DetailRecu $detailRecu)
    {
        //
    }

    public function edit(Vente $vente, Reglement $reglement)
    {
        $comptes = Compte::all();
        $typedetailrecus = TypeDetailRecu::all();
        return view('reglements.edit', compact('vente', 'comptes', 'typedetailrecus', 'reglement'));
    }

    public function update(Request $request, Vente $vente, Reglement $reglement)
    {
        try {

            if ($request->document == NULL) {
                if ($request->compte_id == NULL) {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', Rule::unique('reglements')->ignore($reglement->id)],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ReglementMontantRule($vente, $reglement,)],
                        'typedetailrecu_id' => ['required'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('reglements.edit', ['vente' => $vente->id, 'reglement' => $reglement->id])->withErrors($validator->errors())->withInput();
                    }
                    $reglement = $reglement->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'vente_id' => $vente->id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                        'document' => $request->remoovdoc ? null : $reglement->document,
                        'user_id' => auth()->user()->id
                    ]);

                    if ($reglement) {
                        Session()->flash('message', 'Règlement modifié avec succès.!');
                        return redirect()->route('reglement.index', ['vente' => $vente->id]);
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', Rule::unique('reglements')->ignore($reglement->id)],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ReglementMontantRule($vente, $reglement)],
                        'compte_id' => ['required'],
                        'typedetailrecu_id' => ['required'],


                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('reglements.edit', ['vente' => $vente->id, 'reglement' => $reglement->id])->withErrors($validator->errors())->withInput();
                    }


                    $reglement = $reglement->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'vente_id' => $vente->id,
                        'compte_id' => $request->compte_id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                        'document' => $request->remoovdoc ? null : $reglement->document,
                        'user_id' => auth()->user()->id
                    ]);


                    if ($reglement) {
                        Session()->flash('message', 'Règlement modifié avec succès!');
                        return redirect()->route('reglements.index', ['vente' => $vente->id]);
                    }
                }
            } else {
                if ($request->compte_id == NULL) {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', Rule::unique('reglements')->ignore($reglement->id)],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ReglementMontantRule($vente, $reglement)],
                        'document' => ['required', 'file', 'mimes:pdf,png,jpeg,jpg'],
                        'typedetailrecu_id' => ['required'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('reglements.edit', ['vente' => $vente->id, 'reglement' => $reglement->id])->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les documents dans la base de données */
                    $filename = time() . '.' . $request->document->extension();

                    $file = $request->file('document')->storeAs(
                        'documents',
                        $filename,
                        'public'
                    );

                    $reglement = $reglement->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'document' => $file,
                        'vente_id' => $vente->id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                    ]);


                    if ($reglement) {
                        Session()->flash('message', 'Règlement modifié avec succès!');
                        return redirect()->route('reglements.index', ['vente' => $vente->id]);
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', Rule::unique('reglements')->ignore($reglement->id)],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ReglementMontantRule($vente, $reglement)],
                        'document' => ['required', 'file', 'mimes:pdf,jpeg,jpg,png'],
                        'compte_id' => ['required'],
                        'typedetailrecu_id' => ['required'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('reglements.edit', ['vente' => $vente->id, 'reglement' => $reglement->id])->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les documents dans la base de données */
                    $filename = time() . '.' . $request->document->extension();

                    $file = $request->file('document')->storeAs(
                        'documents',
                        $filename,
                        'public'
                    );

                    $reglement = $reglement->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'document' => $file,
                        'vente_id' => $vente->id,
                        'compte_id' => $request->compte_id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                        'user_id' => auth()->user()->id
                    ]);

                    if ($reglement) {
                        Session()->flash('message', 'Règlement modifié avec succès!');
                        return redirect()->route('reglements.index', ['vente' => $vente->id]);
                    }
                }
            }
        } catch (\Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('reglements.index', ['vente' => $vente->id]);
            }
        }
    }

    public function delete(Vente $vente, Reglement $reglement)
    {
        $comptes = Compte::all();
        $typedetailrecus = TypeDetailRecu::all();
        return view('reglements.delete', compact('vente', 'comptes', 'typedetailrecus', 'reglement'));
    }

    public function destroy(Vente $vente, Reglement $reglement)
    {
        if ($vente->statut != 'Contrôller') {
            if (!$reglement->compte_id) {
                $mouvement = Mouvement::create([
                    'libelleMvt' => "suppression reglement d'achat de ciment",
                    'dateMvt' => Carbon::now(),
                    'montantMvt' => $reglement->montant,
                    'compteClient_id' => $reglement->vente->commandeclient->client->compteClients[0]->id,
                    'user_id' => auth()->user()->id,
                    'sens' => 1,
                    'reglement_id' => $reglement->id,
                    'destroy' => true
                ]);
                /*  if($mouvement){
                    $compte = $mouvement->compteClient;
                    $compte->solde = $compte->solde + $reglement->montant;
                    $compte->update();
                } */
            }

            ControlesTools::generateLog($reglement, 'reglement', 'Suppression ligne');

            $reglement = $reglement->delete();

            if ($reglement) {
                Session()->flash('message', 'Règlement supprimé avec succès!');
                return redirect()->route('reglements.index', ['vente' => $vente->id]);
            }
        } else {
            Session()->flash('message', 'Vous ne pouvez pas supprimer ce règlement!');
            return redirect()->route('reglements.index', ['vente' => $vente->id]);
        }
    }
    public function validerReglement(Vente $vente)
    {
        $reglements = $vente->reglements;
        if (Auth::user()->id != $vente->user->id) {
            Session()->flash('error', "Attention! Vous essayez de valider un règlement d'une vente qui ne vous appartient pas. Ce comportement sera notifié à l'administrateur.");
            // $mail = new SuspectMail(['email' => env('ADMIN_SUSPECT')], "Validation du règlément d'un autre vendeur", Auth::user()->name . "Cherche à valider la vente d'un autre utilisateur " . $vente->user->name);
            // Mail::send($mail);
            return redirect()->route('ventes.index');
        }
        DB::beginTransaction();
        try {
            $vente->update(['statut_reglement' => true]);
            foreach ($reglements as $reglement) {
                $reglement->update(['statut' => 2]);
            }
            DB::commit();
            $user = User::find(env('VALIDATEUR_ID'));
            Session()->flash('message', 'Règlement validé avec succès!');
            // $mail = new NotificationValidationReglement(['email' => $user->email, 'nom' => $user->name], "Validation du règlément d'un autre vendeur", Auth::user()->name . "Cherche à valider la vente d'un autre utilisateur " . $vente->user->name, $vente);
            // Mail::send($mail);
            return redirect()->route('ventes.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }
}
