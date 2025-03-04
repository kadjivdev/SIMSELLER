<?php

namespace App\Http\Controllers;

use App\Models\EcheanceCredit;
use App\Models\User;
use App\tools\CommandeClientTools;
use Exception;
use App\Models\Zone;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Parametre;
use App\Models\TypeCommande;
use Illuminate\Http\Request;
use App\Models\CommandeClient;
use Illuminate\Mail\Transport\Transport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\TryCatch;

class _VenteController extends Controller
{
    public function index()
    {
        $roles = Auth::user()->roles()->pluck('id')->toArray();
        $commandeclients = CommandeClient::whereIn('statut', ['Préparation', 'Vendue', 'Validée', 'Livraison partielle', 'Livrée'])->pluck('id');
        if (in_array(1, $roles) || in_array(2, $roles))
            $ventes = Vente::whereIn('commande_client_id', $commandeclients)->orderByDesc('code')->get();
        elseif (in_array(3, $roles))
            $ventes = Vente::whereIn('commande_client_id', $commandeclients)->where('users', Auth::user()->id)->where('')->orderByDesc('date')->get();

        return view('ventes.index', compact('ventes'));
    }

    public function create(Request $request)
    {
        $typeVente = [];
        $user = User::find(Auth::user()->id);
        $repre = $user->representant;

        $zones = $repre->zones;
        if ($repre->nom == 'DIRECTION') {
            $zones = Zone::all();
        }

        if ($request->statuts) {
            if ($request->statuts == 1) {
                $clients = Client::all();
                //$zones = Zone::all();
                $typecommandeclient = TypeCommande::where('libelle', 'COMPTANT')->first();
                $commandeclients = CommandeClient::whereIn('statut', ['Non livrée', 'Livraison partielle'])->get();
                $req = $request->statuts;
            } elseif ($request->statuts == 2) {
                $clients = Client::all();
                //$zones = Zone::all();
                $typecommandeclient = TypeCommande::where('libelle', 'COMPTANT')->first();
                $commandeclients = CommandeClient::where('statut', 'Validée')->orWhere('statut', 'Livraison partielle')->whereNull('byvente')->where('type_commande_id', 2)->get();
                $req = $request->statuts;
            }
        } else {
            $clients = Client::all();
            //$zones = Zone::all();
            $typecommandeclient = TypeCommande::where('libelle', 'COMPTANT')->first();
            $commandeclients = CommandeClient::whereIn('statut', ['Non livrée', 'Livraison partielle'])->get();
            $req = 1;
        }

        $redirectto = $request->redirectto;
        $vente = NULL;
        return view('ventes.create', compact('vente', 'typecommandeclient', 'clients', 'commandeclients', 'zones', 'redirectto', 'req', 'typeVente'));
    }


    public function store(Request $request)
    {
        try {
            $req = NULL;
            if ($request->statuts == 1) {
                //dd($request->statuts);
                if ($request->type_vente_id == 1) {
                    $validator = Validator::make($request->all(), [
                        'date' => ['required', 'before_or_equal:' . date('Y-m-d')],
                        'client_id' => ['required'],
                        'zone_id' => ['required'],
                        'type_vente_id' => ['required'],
                        'transport' => ['required'],
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'date' => ['required', 'before_or_equal:' . date('Y-m-d')],
                        'client_id' => ['required'],
                        'zone_id' => ['required'],
                        'type_vente_id' => ['required'],
                        'echeance' => ['required', 'after:' . date('Y-m-d')],
                        'transport' => ['required'],
                    ]);
                }

                $req = $request->statut;
                if ($validator->fails()) {
                    //dd($request->statuts);
                    return redirect()->route('ventes.create', ['statuts' => $req])->withErrors($validator->errors())->withInput();
                }

                $format = env('FORMAT_COMMANDE_CLIENT');
                $parametre = Parametre::where('id', env('COMMANDE_CLIENT'))->first();
                $code = $format . str_pad($parametre->valeur, 7, "0", STR_PAD_LEFT);

                $commandeclients = CommandeClient::create([
                    'code' => $code,
                    'dateBon' => $request->date,
                    'statut' => "Préparation",
                    'type_commande_id' => $request->type_vente_id,
                    'client_id' => $request->client_id,
                    'zone_id' => $request->zone_id,
                    'users' => Auth::user()->id,
                    'byvente' => 1
                ]);

                if ($commandeclients) {

                    $valeur = $parametre->valeur;

                    $valeur = $valeur + 1;

                    $parametres = Parametre::find(env('COMMANDE_CLIENT'));

                    $parametre = $parametres->update([
                        'valeur' => $valeur,
                    ]);


                    if ($parametre) {
                        $format = env('FORMAT_VENTE_D');
                        $parametre = Parametre::where('id', env('VENTE'))->first();
                        $code = $format . str_pad($parametre->valeur, 7, "0", STR_PAD_LEFT);
                        $ventes = Vente::create([
                            'code' => $code,
                            'date' => $request->date,
                            'statut' => "Préparation",
                            'commande_client_id' => $commandeclients->id,
                            'users' => Auth::user()->id,
                            'type_vente_id' => $request->type_vente_id,
                            'transport' => $request->transport

                        ]);

                        if ($ventes) {

                            $valeur = $parametre->valeur;

                            $valeur = $valeur + 1;

                            $parametres = Parametre::find(env('VENTE'));

                            $parametres = $parametres->update([
                                'valeur' => $valeur,
                            ]);

                            if ($request->type_vente_id == 2) {
                                EcheanceCredit::create([
                                    'date' => $request->echeance,
                                    'statut' => 0,
                                    'vente_id' => $ventes->id,
                                    'user_id' => auth()->user()->id
                                ]);
                            }

                            if ($parametres) {
                                Session()->flash('message', 'Vente enregistrée avec succès!');
                                return redirect()->route('vendus.create', ['vente' => $ventes->id]);
                            }
                        }
                    }
                }
            } elseif ($request->statuts == 2) {
                if ($request->type_vente_id == 1) {
                    $validator = Validator::make($request->all(), [
                        'date' => ['required', 'before_or_equal:' . date('Y-m-d')],
                        'commande_client_id' => ['required'],
                        'zone_id' => ['required'],
                        'type_vente_id' => ['required'],
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'date' => ['required', 'before_or_equal:' . date('Y-m-d')],
                        'commande_client_id' => ['required'],
                        'zone_id' => ['required'],
                        'type_vente_id' => ['required'],
                        'echeance' => ['required', 'after:' . date('Y-m-d')],
                        'transport' => ['required'],
                    ]);
                }

                $req = $request->statuts;
                if ($validator->fails()) {
                    return redirect()->route('ventes.create', ['statuts' => $req])->withErrors($validator->errors())->withInput();
                }

                $format = env('FORMAT_VENTE_C');
                $parametre = Parametre::where('id', env('VENTE'))->first();
                $code = $format . str_pad($parametre->valeur, 7, "0", STR_PAD_LEFT);

                $ventes = Vente::create([
                    'code' => $code,
                    'date' => $request->date,
                    'statut' => "Préparation",
                    'commande_client_id' => $request->commande_client_id,
                    'users' => Auth::user()->id,
                    'type_vente_id' => $request->type_vente_id,
                    'transport' => $request->transport
                ]);

                if ($ventes) {

                    $valeur = $parametre->valeur;

                    $valeur = $valeur + 1;

                    $parametres = Parametre::find(env('VENTE'));

                    $parametres = $parametres->update([
                        'valeur' => $valeur,
                    ]);
                    if ($request->type_vente_id == 2) {

                        EcheanceCredit::create([
                            'date' => $request->echeance,
                            'statut' => 0,
                            'vente_id' => $ventes->id,
                            'user_id' => auth()->user()->id
                        ]);
                    }

                    if ($parametres) {
                        Session()->flash('message', 'Vente enregistrée avec succès!');
                        return redirect()->route('vendus.create', ['vente' => $ventes->id]);
                    }
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('vendus.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */

    public function show(Vente $vente)
    {
        return view('ventes.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory
     */
    
    public function edit(Request $request, Vente $vente)
    {
        $user = User::find(Auth::user()->id);
        $repre = $user->representant;
        $zones = $repre->zones;
        if ($repre->nom == 'DIRECTION') {
            $zones = Zone::all();
        }
        if ($vente->commandeclient->type_commande_id == 1) {
            $clients = Client::all();
            $typecommandeclient = TypeCommande::where('libelle', 'COMPTANT')->first();
            $commandeclients = CommandeClient::whereIn('statut', ['Non livrée', 'Livraison partielle'])->get();
            $req = $vente->commandeclient->type_commande_id;
        } else {
            $clients = Client::all();
            $typecommandeclient = TypeCommande::where('libelle', 'COMPTANT')->first();
            $commandeclients = CommandeClient::where('statut', 'Non livrée')->orWhere('statut', 'Livraison partielle')->where('type_commande_id', 2)->get();
            $req = $vente->commandeclient->type_commande_id;
        }


        $redirectto = $request->redirectto;
        return view('ventes.edit', compact('vente', 'typecommandeclient', 'clients', 'commandeclients', 'zones', 'redirectto', 'req'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request, Vente $vente)
    {
        try {
            $req = NULL;
            if ($vente->commandeclient->type_commande_id == 1) {

                $validator = Validator::make($request->all(), [
                    'date' => ['required', 'before_or_equal:' . date('Y-m-d')],
                    'client_id' => ['required'],
                    'zone_id' => ['required'],
                    'type_vente_id' => ['required'],
                    'transport' => ['required'],
                ]);
                $req = $vente->commandeclient->type_commande_id;
                if ($validator->fails()) {
                    return redirect()->route('ventes.edit', ['vente' => $vente->id])->withErrors($validator->errors())->withInput();
                }

                $format = env('FORMAT_VENTE_D');
                $parametre = Parametre::where('id', env('VENTE'))->first();
                $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                $vente->commandeclient()->update([
                    'type_commande_id' => $request->type_vente_id,
                    'client_id' => $request->client_id,
                    'zone_id' => $request->zone_id
                ]);
                $vente->update([
                    'date' => $request->date,
                ]);

                Session()->flash('message', 'Vous avez modifier avec succès la vente. Faite la relecture du détails');
                return redirect()->route('vendus.create', ['vente' => $vente->id]);
            } elseif ($vente->commandeclient->type_commande_id == 2) {

                $validator = Validator::make($request->all(), [
                    'date' => ['required'],
                    'commande_client_id' => ['required'],
                    'zone_id' => ['required'],
                    'type_vente_id' => ['required'],
                    'transport' => ['required'],
                ]);
                $req = $vente->commandeclient->type_commande_id;
                if ($validator->fails()) {
                    return redirect()->route('ventes.create', $req)->withErrors($validator->errors())->withInput();
                }

                $vente->update([
                    'date' => $request->date,
                    'commande_client_id' => $request->commande_client_id,
                    'users' => Auth::user()->id,
                    'type_vente_id' => $request->type_vente_id,
                    'transport' => $request->transport
                ]);

                if ($request->type_vente_id == 1) {
                    $vente->echeances()->delete();
                }

                Session()->flash('message', 'Vous avez modifier avec succès la vente. Faite la relecture du détails');
                return redirect()->route('vendus.create', ['vente' => $vente->id]);
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('vendus.index');
            }
        }
    }

    public function delete(Vente $vente)
    {
        if (Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->existe()) {
            return view('ventes.delete', compact('vente'));
        } else {

            Session()->flash('message', 'Vos n\'êtes eligible à une suppression.');
            return view('ventes.index', compact('ventes'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Vente $vente)
    {

        $vente->vendus()->delete();
        $vente->delete();
        return redirect()->route('ventes.index', ['message' => $vente]);
    }

    public function invalider(Vente $vente)
    {
        return view('ventes.invalider', compact('vente'));
    }

    public function posteInvalider(Vente $vente)
    {
        $vente->update(['statut' => 'Préparation']);
        CommandeClientTools::changeStatutCommande($vente->commandeclient);
        return redirect()->route('ventes.index')->with('message', 'Votre vente est passé en préparation.');
    }

    public function validationVente(Vente $vente)
    {
        if ($vente->vendus()->sum('qteVendu') && $vente->vendus()->sum('qteVendu') == $vente->qteTotal) {
            $vente->update(['statut' => 'Vendue']);
            CommandeClientTools::changeStatutCommande($vente->commandeclient);
            return redirect()->route('ventes.index')->with('message', 'Félicitation! Votre vente a été enregistrée');
        } else
            abort(403);
    }

    public function initVente(Vente $vente)
    {
        $vente->update([
            'statut' => 'Préparation',
            'montant' => null,
            'qteTotal' => null,
            'pu' => null,
            'produit_id' => null,
            'remise' => null
        ]);
        $vente->vendus()->delete();
        return redirect()->route('vendus.create', ['vente' => $vente->id])->with('msgSuppression', 'Vente initialisée.');
    }
    public function traiterVente(Request $request, Vente $vente)
    {
        try {
            $data = $request->all();
            $vente->update($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
