<?php

namespace App\Http\Controllers;

use App\tools\CompteTools;
use Exception;
use App\Models\Client;
use App\Models\Parametre;
use App\Models\TypeClient;
use App\tools\ControlesTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $typeclt = TypeClient::whereNotNull('type_client_id')->get();
        $libelleTc = null;
        if (count($typeclt) > 0) {
            $libelleTc = $typeclt[0]->libelle;
            $clients = Client::where('type_client_id', $typeclt[0]->id)->paginate(10);
        } else
            $clients = [];
        $req = null;
        $sel = 'all';

        if ($request->statuts && $request->statuts != 'all') {
            $typeclient = TypeClient::find($request->statuts);
            $libelleTc = $typeclient->libelle;
            $req = $typeclient->parent->id;
            $clients = Client::where('type_client_id', $request->statuts)->paginate(10);
            $sel = $typeclient->id;
        } elseif ($request->status == 'all') {
            $sel = 'all';
            $libelleTc = $typeclt[0]->libelle;
            $clients = Client::where('type_client_id', 3)->paginate(10);
        }

        return view('clients.index', compact('clients', 'req', 'typeclt', 'sel', 'libelleTc'));
    }


    public function create(Request $request)
    {
        $typeclt = TypeClient::whereNotNull('type_client_id')->get();;
        $req = null;
        if ($request->statuts) {
            $type = TypeClient::find($request->statuts);
            $typeclient = $type->parent;
            $req = $request->statuts;
        } else {
            $typeclient = null;
        }

        return view('clients.create', compact('typeclient', 'req', 'typeclt'));
    }


    public function store(Request $request, TypeClient $typeclient)
    {
        try {

            $statuts = $request->statuts;

            if ($typeclient->libelle == env('TYPE_CLIENT_P')) {

                if ($request->photo == NULL) {

                    $validator = Validator::make($request->all(), [
                        'civilite' => ['required', 'string', 'max:255'],
                        'nom' => ['required', 'string', 'max:255'],
                        'prenom' => ['required', 'string', 'max:255'],
                        'telephone' => ['required', 'string', 'max:255', 'unique:clients'],
                        'email' => ['nullable', 'string', 'email', 'max:255', 'unique:clients'],
                        'adresse' => ['nullable', 'string', 'max:255'],
                        'domaine' => ['nullable', 'string', 'max:255'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('clients.create', compact('statuts'))->withErrors($validator->errors())->withInput();
                    }


                    $format = env('FORMAT_CLIENT_P');
                    $parametre = Parametre::where('id', env('CLIENT'))->first();
                    $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                    $clients = Client::create([
                        'code' => $code,
                        'civilite' => ucwords($request->civilite),
                        'nom' => strtoupper($request->nom),
                        'prenom' => ucwords($request->prenom),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'statutCredit' => $request->statutCredit ? $request->statutCredit : 0,
                        'adresse' => $request->adresse,
                        'domaine' => $request->domaine,
                        'type_client_id' => $statuts,
                    ]);

                    if ($clients) {

                        $valeur = $parametre->valeur;

                        $valeur = $valeur + 1;

                        $parametres = Parametre::find(env('CLIENT'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);
                        $compteClient = CompteTools::addCompte($clients->id, auth()->user()->id);
                        if ($parametre && $compteClient) {
                            Session()->flash('message', 'Client ajouté avec succès!');
                            return redirect()->route('clients.index', compact('statuts'));
                        }
                    }
                } else {
                    $statuts = $request->statuts;
                    $validator = Validator::make($request->all(), [
                        'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
                        'civilite' => ['required', 'string', 'max:255'],
                        'nom' => ['required', 'string', 'max:255'],
                        'prenom' => ['required', 'string', 'max:255'],
                        'telephone' => ['required', 'string', 'max:255', 'unique:clients'],
                        'email' => ['nullable', 'string', 'email', 'max:255', 'unique:clients'],
                        'adresse' => ['nullable', 'string', 'max:255'],
                        'domaine' => ['nullable', 'string', 'max:255'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('clients.create', compact('statuts'))->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les images dans la base de données */
                    $image = $request->file('photo');
                    $photo = time() . '.' . $image->extension();
                    $image->move(public_path('images'), $photo);

                    $format = env('FORMAT_CLIENT_P');
                    $parametre = Parametre::where('id', env('CLIENT'))->first();
                    $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                    $clients = Client::create([
                        'code' => $code,
                        'civilite' => ucwords($request->civilite),
                        'nom' => strtoupper($request->nom),
                        'prenom' => ucwords($request->prenom),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'statutCredit' => $request->statutCredit ? $request->statutCredit : 0,
                        'adresse' => $request->adresse,
                        'domaine' => $request->domaine,
                        'type_client_id' => $typeclient->id,
                        'photo' => $photo,
                    ]);

                    if ($clients) {

                        $valeur = $parametre->valeur;

                        $valeur = $valeur + 1;

                        $parametres = Parametre::find(env('CLIENT'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);
                        $compteClient = CompteTools::addCompte($clients->id, auth()->user()->id);
                        if ($parametre && $compteClient) {
                            Session()->flash('message', 'Client ajouté avec succès!');
                            return redirect()->route('clients.index', compact('statuts'));
                        }
                    }
                }
            } elseif ($typeclient->libelle == env('TYPE_CLIENT_S')) {

                if ($request->logo == NULL) {

                    $validator = Validator::make($request->all(), [
                        'sigle' => ['required', 'string', 'max:255', 'unique:clients'],
                        'raisonSociale' => ['required', 'string', 'max:255'],
                        'telephone' => ['required', 'string', 'max:255', 'unique:clients'],
                        'email' => ['nullable', 'string', 'email', 'max:255', 'unique:clients'],
                        'adresse' => ['nullable', 'string', 'max:255'],
                        'domaine' => ['nullable', 'string', 'max:255'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('clients.create', compact('statuts'))->withErrors($validator->errors())->withInput();
                    }

                    $format = env('FORMAT_CLIENT_S');
                    $parametre = Parametre::where('id', env('CLIENT'))->first();
                    $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                    $clients = Client::create([
                        'code' => $code,
                        'sigle' => strtoupper($request->sigle),
                        'raisonSociale' => strtoupper($request->raisonSociale),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'statutCredit' => $request->statutCredit ? $request->statutCredit : 0,
                        'adresse' => $request->adresse,
                        'domaine' => $request->domaine,
                        'type_client_id' => $statuts,
                    ]);

                    if ($clients) {

                        $valeur = $parametre->valeur;

                        $valeur = $valeur + 1;

                        $parametres = Parametre::find(env('CLIENT'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);
                        $compteClient = CompteTools::addCompte($clients->id, auth()->user()->id);
                        if ($parametre && $compteClient) {
                            Session()->flash('message', 'Client ajouté avec succès!');
                            return redirect()->route('clients.index', compact('statuts'));
                        }
                    }
                } else {

                    $validator = Validator::make($request->all(), [
                        'logo' => ['required', 'image', 'mimes:jpg,bmp,png'],
                        'sigle' => ['required', 'string', 'max:255', 'unique:clients'],
                        'raisonSociale' => ['required', 'string', 'max:255'],
                        'telephone' => ['required', 'string', 'max:255', 'unique:clients'],
                        'email' => ['nullable', 'string', 'email', 'max:255', 'unique:clients'],
                        'adresse' => ['nullable', 'string', 'max:255'],
                        'domaine' => ['nullable', 'string', 'max:255'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('clients.create', compact('statuts'))->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les images dans la base de données */
                    $image = $request->file('logo');
                    $logo = time() . '.' . $image->extension();
                    $image->move(public_path('images'), $logo);

                    $format = env('FORMAT_CLIENT_S');
                    $parametre = Parametre::where('id', env('CLIENT'))->first();
                    $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                    $clients = Client::create([
                        'code' => $code,
                        'sigle' => strtoupper($request->sigle),
                        'raisonSociale' => strtoupper($request->raisonSociale),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'statutCredit' => $request->statutCredit ? $request->statutCredit : 0,
                        'adresse' => $request->adresse,
                        'domaine' => $request->domaine,
                        'type_client_id' => $statuts,
                        'logo' => $logo,
                    ]);

                    if ($clients) {

                        $valeur = $parametre->valeur;

                        $valeur = $valeur + 1;

                        $parametres = Parametre::find(env('CLIENT'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);
                        $compteClient = CompteTools::addCompte($clients->id, auth()->user()->id);
                        if ($parametre && $compteClient) {
                            Session()->flash('message', 'Client ajouté avec succès!');
                            return redirect()->route('clients.index', compact('statuts'));
                        }
                    }
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('clients.create');
            }
        }
    }



    public function show(Client $client)
    {
        //
    }



    public function edit(Request $request, Client $client)
    {
        $typeclt = TypeClient::whereNotNull('type_client_id')->get();;
        $req = null;
        if ($request->statuts) {
            $type = TypeClient::find($request->statuts);
            $typeclient = $type->parent;
            $req = $request->statuts;
        } else {
            $typeclient = null;
        }

        return view('clients.edit', compact('typeclient', 'req', 'typeclt', 'client'));
    }



    public function update(Request $request, TypeClient $typeclient, Client $client)
    {
        try {
            if ($typeclient->parent->libelle == env('TYPE_CLIENT_P')) {
                $statuts = $request->statuts;
                if ($request->photo == NULL) {

                    $validator = Validator::make($request->all(), [
                        'civilite' => ['required', 'string', 'max:255'],
                        'nom' => ['required', 'string', 'max:255'],
                        'prenom' => ['required', 'string', 'max:255'],
                        'telephone' => ['required', 'string', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'adresse' => ['nullable', 'string', 'max:255'],
                        'domaine' => ['nullable', 'string', 'max:255'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('clients.create', compact('statuts'))->withErrors($validator->errors())->withInput();
                    }


                    $clients = $client->update([
                        'code' => $request->code,
                        'civilite' => ucwords($request->civilite),
                        'nom' => strtoupper($request->nom),
                        'prenom' => ucwords($request->prenom),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'statutCredit' => $request->statutCredit ? $request->statutCredit : 0,
                        'adresse' => $request->adresse,
                        'domaine' => $request->domaine,
                        'type_client_id' => $typeclient->id,
                        'photo' => $request->remoov ? null : $client->photo
                    ]);

                    if ($clients) {
                        Session()->flash('message', 'Client modifié avec succès!');
                        return redirect()->route('clients.index', compact('statuts'));
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
                        'civilite' => ['required', 'string', 'max:255'],
                        'nom' => ['required', 'string', 'max:255'],
                        'prenom' => ['required', 'string', 'max:255'],
                        'telephone' => ['required', 'string', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'adresse' => ['nullable', 'string', 'max:255'],
                        'domaine' => ['nullable', 'string', 'max:255'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('clients.edit', compact('statuts'))->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les images dans la base de données */
                    $image = $request->file('photo');
                    $photo = time() . '.' . $image->extension();
                    $image->move(public_path('images'), $photo);

                    $clients = $client->update([
                        'code' => $request->code,
                        'civilite' => ucwords($request->civilite),
                        'nom' => strtoupper($request->nom),
                        'prenom' => ucwords($request->prenom),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'statutCredit' => $request->statutCredit ? $request->statutCredit : 0,
                        'adresse' => $request->adresse,
                        'domaine' => $request->domaine,
                        'type_client_id' => $typeclient->id,
                        'photo' => $request->remoov ? null : $photo,
                    ]);

                    if ($clients) {
                        Session()->flash('message', 'Client modifié avec succès!');
                        return redirect()->route('clients.index', compact('statuts'));
                    }
                }
            } elseif ($typeclient->parent->libelle == env('TYPE_CLIENT_S')) {

                $statuts = $request->statuts;

                if ($request->logo == NULL) {

                    $validator = Validator::make($request->all(), [
                        'sigle' => ['required', 'string', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'raisonSociale' => ['required', 'string', 'max:255'],
                        'telephone' => ['required', 'string', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'adresse' => ['nullable', 'string', 'max:255'],
                        'domaine' => ['nullable', 'string', 'max:255'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('clients.create', compact('statuts'))->withErrors($validator->errors())->withInput();
                    }

                    $clients = $client->update([
                        'code' => $request->code,
                        'sigle' => strtoupper($request->sigle),
                        'raisonSociale' => strtoupper($request->raisonSociale),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'statutCredit' => $request->statutCredit ? $request->statutCredit : 0,
                        'adresse' => $request->adresse,
                        'domaine' => $request->domaine,
                        'type_client_id' => $typeclient->id,
                        'logo' => $request->remoov ? null : $client->logo,
                    ]);

                    if ($clients) {

                        Session()->flash('message', 'Client modifié avec succès!');
                        return redirect()->route('clients.index', compact('statuts'));
                    }
                } else {

                    $validator = Validator::make($request->all(), [
                        'logo' => ['required', 'image', 'mimes:jpg,bmp,png'],
                        'sigle' => ['required', 'string', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'raisonSociale' => ['required', 'string', 'max:255'],
                        'telephone' => ['required', 'string', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
                        'adresse' => ['nullable', 'string', 'max:255'],
                        'domaine' => ['nullable', 'string', 'max:255'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('clients.edit', compact('statuts'))->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les images dans la base de données */
                    $image = $request->file('logo');
                    $logo = time() . '.' . $image->extension();
                    $image->move(public_path('images'), $logo);

                    $clients = $client->update([
                        'code' => $request->code,
                        'sigle' => strtoupper($request->sigle),
                        'raisonSociale' => strtoupper($request->raisonSociale),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'statutCredit' => $request->statutCredit ? $request->statutCredit : 0,
                        'adresse' => $request->adresse,
                        'domaine' => $request->domaine,
                        'type_client_id' => $typeclient->id,
                        'logo' => $request->remoov ? null : $logo,
                    ]);

                    if ($clients) {
                        Session()->flash('message', 'Client modifié avec succès!');
                        return redirect()->route('clients.index', compact('statuts'));
                    }
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('clients.create');
            }
        }
    }


    public function sommeil(Request $request, Client $client)
    {
        try {
            if ($client->typeclient->parent->libelle == env('TYPE_CLIENT_P')) {
                $statuts = $client->typeclient->id;
                $client->sommeil = 1;
                $clients = $client->update();

                if ($clients) {
                    Session()->flash('message', 'Client mise en sommeil avec succès!');
                    return redirect()->route('clients.index', compact('statuts'));
                }
            } elseif ($client->typeclient->parent->libelle == env('TYPE_CLIENT_S')) {

                $statuts = $client->typeclient->id;
                $client->sommeil = 1;
                $clients = $client->update();

                if ($clients) {
                    Session()->flash('message', 'Client mise en sommeil avec succès!');
                    return redirect()->route('clients.index', compact('statuts'));
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('clients.index');
            }
        }
    }


    public function reveil(Request $request, Client $client)
    {
        try {
            if ($client->typeclient->parent->libelle == env('TYPE_CLIENT_P')) {
                $statuts = $client->typeclient->id;
                $client->sommeil = 0;
                $clients = $client->update();

                if ($clients) {
                    Session()->flash('message', 'Client opérationnel!');
                    return redirect()->route('clients.index', compact('statuts'));
                }
            } elseif ($client->typeclient->parent->libelle == env('TYPE_CLIENT_S')) {

                $statuts = $client->typeclient->id;
                $client->sommeil = 0;
                $clients = $client->update();

                if ($clients) {
                    Session()->flash('message', 'Client opérationnel!');
                    return redirect()->route('clients.index', compact('statuts'));
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('clients.index');
            }
        }
    }


    public function delete(Client $client)
    {
        $statuts = $client->typeclient->id;
        if (count($client->commandeclients) > 0) {
            Session()->flash('messageSupp', 'Vous ne pouvez pas supprimer le client car il a déjà des commandes');
            return redirect()->route('clients.index', ['statuts' => $statuts]);
        }
        return view('clients.delete', compact('client'));
    }



    public function destroy(Client $client)
    {
        $statuts = NULL;
        ControlesTools::generateLog($client, 'Client', 'Suppression ligne');

        $clients = $client->delete();

        if ($client->typeclient->libelle == env('TYPE_CLIENT_P')) {
            $statuts = 1;
        } elseif ($client->typeclient->libelle == env('TYPE_CLIENT_S')) {
            $statuts = 2;
        }

        if ($clients) {
            Session()->flash('message', 'Client supprimé avec succès!');
            return redirect()->route('clients.index', compact('statuts'));
        }
    }
    public function achatClient(Client $client)
    {
        $achatClients =  DB::table('commande_clients')->Join('clients', 'clients.id', '=', 'commande_clients.client_id')
            ->Join('ventes', 'commande_client_id', '=', 'commande_clients.id')
            ->Join('users', 'users.id', '=', 'ventes.users')
            ->Join('type_ventes', 'type_ventes.id', '=', 'ventes.type_vente_id')
            ->where('commande_clients.client_id', $client->id)
            ->where('ventes.statut', 'Vendue')
            ->select('ventes.code', 'ventes.date', 'ventes.montant', 'type_ventes.libelle', 'users.name')->get();
        return view('client.achatClient', compact('client', 'achatClients'));
    }

    public function oldSoldeClient()
    {
        $clientolds =   DB::select(
            "SELECT clientOld.nomUp, clientOld.creditUP, clientOld.debitUP, clients.credit, clients.debit
            FROM clients
            INNER JOIN  clientOld ON clientOld.nomUP = clients.raisonSociale ;"
        );
        $credit =   DB::select(
            "SELECT sum(clientOld.creditUP) as credit FROM clientOld ;"
        );
        $debit =   DB::select(
            "SELECT sum(clientOld.debitUP) as debit FROM clientOld ;"
        );
        $solde =   DB::select(
            "SELECT (sum(clientOld.creditUP)+sum(clientOld.debitUP))as solde FROM clientOld ;"
        );

        return view('editions.pointSoldeOld', compact('clientolds', 'credit', 'debit', 'solde'));
    }
}
