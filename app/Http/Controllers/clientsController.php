<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Departement;
use App\Models\Porteuille;
use App\Models\TypeClient;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class clientsController extends Controller
{
    //
    /**
     * 
     */
    
    public function index(Request $request)
    {
        if ($request->search) {
            // $clients = Client::where('raisonSociale', 'like', '%'. $request->search. '%')->paginate(1000);
            $clients = Client::where('raisonSociale', 'like', '%' . $request->search . '%')->get();
        } else {
            // $clients = Client::orderBy('id','desc')->paginate(1000);          
            $clients = Client::orderBy('id', 'desc')->get();
        }

        return view('client.index', compact('clients'));
        // <!-- {{ $clients->links('pagination') }}  à ajouter à la fin du tableau pour affichage de la pagination laravel -->

    }
    public function data()
    {
        $clients = Client::orderBy('raisonsociale')->get();
        return response()->json($clients, 200);
    }

    public function create()
    {
        $typeclients = TypeClient::all();
        $departements = Departement::all();
        $agents = Agent::all();
        $filleulFisc = Client::orderBy("id","desc")->whereNotNull('ifu')->get();
        return view('client.create', compact('typeclients', 'departements', 'agents', 'filleulFisc'));
    }

    public function show(Client $client)
    {

        $Porteuilles = Porteuille::join('clients', 'portefeuilles.client_id', '=', 'clients.id')
            ->join('agents', 'portefeuilles.agent_id', '=', 'agents.id')
            ->select(
                'agents.nom',
                'agents.prenom',
                'agents.telephone',
                'portefeuilles.statut',
                'portefeuilles.datedebut',
                'portefeuilles.datefin',
                'portefeuilles.id'
            )
            ->where('portefeuilles.client_id', '=', $client->id)
            ->orderBy('portefeuilles.id', 'desc')
            ->get();


        // Parcours pour vérification de la date de cloture de l'agent.


        $date_exp_verif = DB::table('portefeuilles')
            ->select('*')
            ->where('portefeuilles.client_id', '=', $client->id)
            ->where('portefeuilles.statut', '=', $client->id)
            ->first();

        if ($date_exp_verif) {
            if ($date_exp_verif->datefin < date("Y-m-d")) {
                $desactive = Porteuille::find($date_exp_verif->id);
                $desactive->statut = 0;
                $desactive->update();
            }
        }

        $departements = Departement::all();
        $agents = Agent::all();
        return view('client.portefeuilles', compact('client', 'departements', 'agents', 'Porteuilles'));
    }

    /**
     * Affectation d'agent un client 
     *
     * @param Request $request
     * 
     */
    public function affectionAgent(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'datedebut' => ['required', 'date'],
                'datefin' => ['required', 'date'],
                'agent_id' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('newclient.show', $request->client_id)->withErrors($validator->errors())->withInput();
            }

            $verification = Porteuille::all()
                ->where('client_id', '=', $request->client_id)
                ->where('statut', '=', 1)
                ->first();

            if ($verification) {

                // Cette partie désactiver d'abord avant d'effectuer la nouvelle affectation

                $desactive = Porteuille::find($verification->id);
                $desactive->statut = 0;
                $desactive->update();

                // Cette fait l'affectation

                $porteuille = new Porteuille();
                $porteuille->datedebut = $request->datedebut;
                $porteuille->datefin = $request->datefin;
                $porteuille->client_id = $request->client_id;
                $porteuille->agent_id = $request->agent_id;
                $porteuille->statut = 1;
                $porteuille->save();

                Session()->flash('message', 'Affectation effectué avec succès!');
                return back();
            } else {

                $porteuille = new Porteuille();
                $porteuille->datedebut = $request->datedebut;
                $porteuille->datefin = $request->datefin;
                $porteuille->client_id = $request->client_id;
                $porteuille->agent_id = $request->agent_id;
                $porteuille->statut = 1;
                $porteuille->save();

                Session()->flash('message', 'Affectation effectué avec succès!');
                return redirect()->route('newclient.show', $request->client_id);
            }
        } catch (\Throwable $e) {

            if (env('APP_DEBUG') == TRUE) {

                return $e;
            } else {

                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('newclient.create');
            }
        }
    }
    // Liste des agents affecter à un client.
    public function affectationAgentListe()
    {
    }

    public function edit(Client $client)
    {

        $typeclients = TypeClient::all();
        $departements = Departement::all();
        $filleulFisc = Client::where('ifu', 0)->get();

        // dd( $valeurFisc);
        return view('client.edit', compact('client', 'typeclients', 'departements', 'filleulFisc'));
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'logo' => ['nullable', 'image', 'mimes:jpg,bmp,png'],
                'bordereau_receit' => ['required', 'file'],
                'sigle' => ['nullable', 'string', 'max:255', 'unique:clients'],
                'raisonsociale' => ['required', 'string', 'max:255'],
                'telephone' => ['required', 'string', 'max:255', 'unique:clients'],
                'email' => ['nullable', 'string', 'email', 'max:255', 'unique:clients'],
                'adresse' => ['required', 'string', 'max:255'],
                'domaine' => ['nullable', 'string', 'max:255'],
                'rccm' => ['required', 'string', 'max:255'],
                'parent' => ['nullable'],
                'ifu' => ['nullable'],
                'numerocompte' => ['required', 'string', 'unique:clients,numerocompte'],
                'departement_id' => ['required', 'integer'],
                'type_client_id' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {

                return redirect()->route('newclient.create')->withErrors($validator->errors())->withInput();
            }
            if ($request->agent_id != null) {
                $validator1 = Validator::make($request->all(), [
                    'datedebut' => ['required', 'date'],
                    'datefin' => ['required', 'date'],
                ]);
                if ($validator1->fails()) {
                    return redirect()->route('newclient.create')->withErrors($validator->errors())->withInput();
                }
            }
            /* Uploader les images dans la base de données */
            $logo = "";
            if ($request->file != null) {

                $image = $request->file('logo');
                $logo = time() . '.' . $image->extension();
                $image->move(public_path('images'), $logo);
            }

            // TRAITEMENT DU BORDEAREAU DE RECU
            if ($request->file("bordereau_receit")) {
                $rc = $request->file("bordereau_receit");
                $rc_name = $rc->getClientOriginalName();

                $rc->move("files", $rc_name);

                ##___Formation du lien du fichier à entregistrer
                $bordereau_receit = asset("/files/".$rc_name);
            }

            $data = [
                "raisonSociale" => $request->raisonsociale,
                "logo" => $logo,
                "bordereau_receit" => $bordereau_receit,
                "ifu" => $request->ifu,
                "rccm" => $request->rccm,
                "telephone" => $request->telephone,
                "email" => $request->email,
                "adresse" => $request->adresse,
                "domaine" => $request->domaine,
                "sigle" => $request->sigle,
                "type_client_id" => $request->type_client_id,
                "numerocompte" => $request->numerocompte,
                "parent" => 0,
                "credit" => 0,
                "departement_id" => $request->departement_id,
            ];

            // dd($data);
            $client = Client::create($data);

            if ($request->agent_id != null) {
                $agent = Agent::find($request->agent_id);
                $portefeuille = new Porteuille();
                $portefeuille->datedebut = $request->datedebut;
                $portefeuille->datefin = $request->datefin;
                $portefeuille->statut =  1;
                $portefeuille->client_id = $client->id;
                $portefeuille->agent_id = $request->agent_id;
                $portefeuille->save();
            }

            Session()->flash('message', 'Client ajoutée avec succès!');
            return redirect()->route('newclient.index');
        } catch (\Throwable $e) {

            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('newclient.create');
            }
        }
    }

    public function eligibleCredit(Client $client)
    {
        try {
            if ($client->statutCredit == 0) {
                $client->statutCredit = 1;
            } else {
                $client->statutCredit = 0;
            }
            $client->update();

            return back();
        } catch (\Throwable $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('newclient.index');
            }
        }
    }

    public function update(Request $request, Client $client)
    {
        try {


            $validator = Validator::make($request->all(), [
                'logo' => ['nullable', 'image', 'mimes:jpg,bmp,png'],
                'sigle' => ['nullable', 'string', 'max:255'],
                'raisonsociale' => ['required', 'string', 'max:255'],
                'telephone' => ['required', 'string', 'max:255'],
                'email' => ['nullable', 'string', 'email', 'max:255'],
                'adresse' => ['nullable', 'string', 'max:255'],
                'domaine' => ['nullable', 'string', 'max:255'],
                'rccm' => ['nullable', 'string', 'max:255'],
                'ifu' => ['nullable'],
                'numerocompte' => ['nullable', 'string'],
                'departement_id' => ['required', 'integer'],
                'type_client_id' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {

                return back()->withErrors($validator->errors())->withInput();
            }


            $logo = "";
            /* Uploader les images dans la base de données */
            if ($request->file('logo')) {
                $image = $request->file('logo');
                $logo = time() . '.' . $image->extension();
                $image->move(public_path('images'), $logo);
            } else {
                $logo =  $client->logo;
            }

            $client->raisonSociale = $request->raisonsociale;
            $client->logo = $logo;
            $client->ifu = $request->ifu;
            $client->rccm = $request->rccm;
            $client->telephone = $request->telephone;
            $client->email = $request->email;
            $client->adresse = $request->adresse;
            $client->domaine = $request->domaine;
            $client->sigle = $request->sigle;
            $client->type_client_id = $request->type_client_id;
            $client->numerocompte = $request->numerocompte;
            $client->departement_id = $request->departement_id;
            $client->parent =  $client->parent;
            $client->filleulFisc =  json_encode($request->filleulFisc);
            $client->update();
            Session()->flash('message', 'Client Modifié avec succès!');
            return redirect()->route('newclient.index');
        } catch (\Throwable $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('newclient.create');
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
        return view('client.delete', compact('client'));
    }

    public function destroy(Client $client)
    {
        $statuts = NULL;

        $clients = $client->delete();


        if ($clients) {
            Session()->flash('message', 'Client supprimé avec succès!');
            return redirect()->route('newclient.index', compact('statuts'));
        }
    }

    public function achatClient(Client $client)
    {

        $achatClients =  DB::table('commande_clients')->Join('clients', 'clients.id', '=', 'commande_clients.client_id')
            ->Join('ventes', 'commande_client_id', '=', 'commande_clients.id')
            ->Join('users', 'users.id', '=', 'ventes.users')
            ->Join('type_commandes', 'type_commandes.id', '=', 'ventes.type_vente_id')
            ->where('commande_clients.client_id', $client->id)
            ->where('ventes.statut', 'Vendue')
            ->where('type_commandes.id', 2)
            ->select('ventes.code', 'ventes.date', 'ventes.montant', 'type_commandes.libelle', 'users.name', 'type_commandes.id')->get();

        $achatComptantSolde = Vente::where('ventes.statut', 'Vendue')
            ->Join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
            ->Join('clients', 'clients.id', '=', 'commande_clients.client_id')
            ->Join('users', 'users.id', '=', 'ventes.users')
            ->Join('type_commandes', 'type_commandes.id', '=', 'ventes.type_vente_id')
            ->where('commande_clients.client_id', $client->id)
            ->where('type_commandes.id', 1)
            ->select('ventes.code', 'ventes.date', 'ventes.montant', 'ventes.statut_reglement', 'ventes.remise', 'type_commandes.libelle', 'users.name', 'type_commandes.id')->get();
        // dd($achatComptantSolde);
        return view('client.achatClient', compact('client', 'achatClients', 'achatComptantSolde'));
    }

    public function recherche(Request $request)
    {
        $clients = Client::where('raisonSociale', 'like', '%' . $request->recherche . '%')->get();
        return view('client.index', compact('clients'));
    }
}
