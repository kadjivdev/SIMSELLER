<?php

namespace App\Http\Controllers;

use App\Models\Banque;
use App\Models\BonCommande;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\DeletedVente;
use App\Models\DetailBonCommande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\Programmation;
use App\Models\Vendu;
use App\Models\Vente;
use App\Models\Reglement;
use App\Models\Representant;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditionController extends Controller
{
    public function pointStock()
    {
        $produits = Produit::all();
        $zones = Zone::all();
        return view('editions.pointStock', compact('produits', 'zones'));
    }

    public function postPointStock(Request $request)
    {
        $zone = Zone::find($request->zone);
        $produit = Produit::find($request->produit);
        if (!$request->zone && !$request->produit) {
            if ($request->debut && $request->fin) {
                $detailboncommandes = DetailBonCommande::all()->pluck('id');
                $programmations = Programmation::where('statut', 'Livrer')->whereBetween('datelivrer', [$request->debut, $request->fin])->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();
                $newProgrammation = [];

                foreach ($programmations as $programmation) {
                    $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
                    if ($qteVendu <= $programmation->qtelivrer) {

                        $newProgrammation[$programmation->id]['programmation'] = $programmation;
                        $newProgrammation[$programmation->id]['qteVendu'] = $qteVendu;
                    }
                }
                return redirect()->route('edition.stock')->withInput()->with('resultat', ['type' => 1, 'programmations' => $newProgrammation, 'produit' => $produit, 'zone' => $zone, 'debut' => $request->debut, 'fin' => $request->fin]);
            }
            $programmations = Programmation::where('statut', 'Livrer')->with('camion')->get();
            $newProgrammation = [];
            foreach ($programmations as $programmation) {
                $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
                if ($qteVendu <= $programmation->qtelivrer) {
                    $newProgrammation[$programmation->id]['programmation'] = $programmation;
                    $newProgrammation[$programmation->id]['qteVendu'] = $qteVendu;
                }
            }
            return redirect()->route('edition.stock')->withInput()->with('resultat', ['type' => 1, 'programmations' => $newProgrammation, 'produit' => $produit, 'zone' => $zone]);
        }
        if ($request->produit && !$request->zone) {
            if ($request->debut && $request->fin) {
                $detailboncommandes = DetailBonCommande::where('produit_id', $request->produit)->pluck('id');
                $programmations = Programmation::where('statut', 'Livrer')->whereBetween('dateprogrammer', [$request->debut, $request->fin])->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();
                $newProgrammation = [];
                foreach ($programmations as $programmation) {
                    $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
                    if ($qteVendu <= $programmation->qtelivrer) {

                        $newProgrammation[$programmation->id]['programmation'] = $programmation;
                        $newProgrammation[$programmation->id]['qteVendu'] = $qteVendu;
                    }
                }
                return redirect()->route('edition.stock')->withInput()->with('resultat', ['type' => 1, 'programmations' => $newProgrammation, 'produit' => $produit, 'zone' => $zone, 'debut' => $request->debut, 'fin' => $request->fin]);
            }
            $detailboncommandes = DetailBonCommande::where('produit_id', $request->produit)->pluck('id');
            $programmations = Programmation::where('statut', 'Livrer')->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();
            $newProgrammation = [];
            foreach ($programmations as $programmation) {
                $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
                if ($qteVendu <= $programmation->qtelivrer) {

                    $newProgrammation[$programmation->id]['programmation'] = $programmation;
                    $newProgrammation[$programmation->id]['qteVendu'] = $qteVendu;
                }
            }
            return redirect()->route('edition.stock')->withInput()->with('resultat', ['type' => 1, 'programmations' => $newProgrammation, 'produit' => $produit, 'zone' => $zone]);
        }
        if (!$request->produit && $request->zone) {
            if ($request->debut && $request->fin) {

                $detailboncommandes = DetailBonCommande::all()->pluck('id');
                $programmations = Programmation::where('statut', 'Livrer')->whereBetween('dateprogrammer', [$request->debut, $request->fin])->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();
                $newProgrammation = [];
                foreach ($programmations as $programmation) {
                    $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
                    if ($qteVendu <= $programmation->qtelivrer && $programmation->zone_id == $request->zone) {
                        $newProgrammation[$programmation->id]['programmation'] = $programmation;
                        $newProgrammation[$programmation->id]['qteVendu'] = $qteVendu;
                    }
                }
                return redirect()->route('edition.stock')->withInput()->with('resultat', ['type' => 1, 'programmations' => $newProgrammation, 'produit' => $produit, 'zone' => $zone, 'debut' => $request->debut, 'fin' => $request->fin]);
            }
            $detailboncommandes = DetailBonCommande::all()->pluck('id');
            $programmations = Programmation::where('statut', 'Livrer')->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();
            $newProgrammation = [];
            foreach ($programmations as $programmation) {
                $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
                if ($qteVendu <= $programmation->qtelivrer && $programmation->zone_id == $request->zone) {
                    $newProgrammation[$programmation->id]['programmation'] = $programmation;
                    $newProgrammation[$programmation->id]['qteVendu'] = $qteVendu;
                }
            }
            return redirect()->route('edition.stock')->withInput()->with('resultat', ['type' => 1, 'programmations' => $newProgrammation, 'produit' => $produit, 'zone' => $zone]);
        }

        if ($request->produit && $request->zone) {
            if ($request->debut && $request->fin) {
                $detailboncommandes = DetailBonCommande::where('produit_id', $request->produit)->pluck('id');
                $programmations = Programmation::where('statut', 'Livrer')->whereBetween('dateprogrammer', [$request->debut, $request->fin])->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();
                $newProgrammation = [];
                foreach ($programmations as $programmation) {
                    $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
                    if ($qteVendu <= $programmation->qtelivrer && $programmation->zone_id == $request->zone) {
                        $newProgrammation[$programmation->id]['programmation'] = $programmation;
                        $newProgrammation[$programmation->id]['qteVendu'] = $qteVendu;
                    }
                }
                return redirect()->route('edition.stock')->withInput()->with('resultat', ['type' => 1, 'programmations' => $newProgrammation, 'produit' => $produit, 'zone' => $zone, 'debut' => $request->debut, 'fin' => $request->fin]);
            }
            $detailboncommandes = DetailBonCommande::where('produit_id', $request->produit)->pluck('id');
            $programmations = Programmation::where('statut', 'Livrer')->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();
            $newProgrammation = [];
            foreach ($programmations as $programmation) {
                $qteVendu = Vendu::where('programmation_id', $programmation->id)->sum('qteVendu');
                if ($qteVendu <= $programmation->qtelivrer && $programmation->zone_id == $request->zone) {
                    $newProgrammation[$programmation->id]['programmation'] = $programmation;
                    $newProgrammation[$programmation->id]['qteVendu'] = $qteVendu;
                }
            }
            return redirect()->route('edition.stock')->withInput()->with('resultat', ['type' => 1, 'programmations' => $newProgrammation, 'produit' => $produit, 'zone' => $zone]);
        }
    }

    public function pointStockValider(Request $request)
    {
        $produits = Produit::all();
        $zones = Zone::all();
        return view('editions.pointStockNonLivrer', compact('produits', 'zones'));
    }

    public function postPointStockValider(Request $request)
    {
        $zone = Zone::find($request->zone);
        $produit = Produit::find($request->produit);
        if (!$request->zone && !$request->produit) {
            $detailboncommandes = DetailBonCommande::all()->pluck('id');
            $programmations = Programmation::where('statut', 'Valider')->where('imprimer', 1)->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();

            return redirect()->route('edition.stockValider')->withInput()->with('resultat', ['type' => 1, 'programmations' => $programmations, 'produit' => $produit, 'zone' => $zone]);
        }
        if ($request->produit && !$request->zone) {
            $detailboncommandes = DetailBonCommande::where('produit_id', $request->produit)->pluck('id');
            $programmations = Programmation::where('statut', 'Valider')->where('imprimer', 1)->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();

            return redirect()->route('edition.stockValider')->withInput()->with('resultat', ['type' => 1, 'programmations' => $programmations, 'produit' => $produit, 'zone' => $zone]);
        }
        if (!$request->produit && $request->zone) {
            $detailboncommandes = DetailBonCommande::all()->pluck('id');
            $programmations = Programmation::where('statut', 'Valider')->where('imprimer', 1)
                ->where('zone_id', $request->zone)->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();

            return redirect()->route('edition.stockValider')->withInput()->with('resultat', ['type' => 1, 'programmations' => $programmations, 'produit' => $produit, 'zone' => $zone]);
        }

        if ($request->produit && $request->zone) {
            $detailboncommandes = DetailBonCommande::where('produit_id', $request->produit)->pluck('id');
            $programmations = Programmation::where('statut', 'Valider')
                ->where('imprimer', 1)->where('zone_id', $request->zone)->whereIn('detail_bon_commande_id', $detailboncommandes)->with('camion')->get();
            $zonex = $zone;
            return redirect()->route('edition.stockValider')->withInput()->with('resultat', ['type' => 1, 'programmations' => $programmations, 'produit' => $produit, 'zone' => $zone, 'zonex' => $zonex]);
        }
    }

    public function pointSolde()
    {
        $clients = Client::all();
        $zones = Zone::all();
        $reglements = Reglement::whereNotNull("vente_id")->sum('montant');
        $sommeVentes = Vente::all()->sum('montant');

        // LES REGLEMENTS SUR LE COMPTE DES CLIENTS
        $credit = Reglement::where("for_dette",false)->whereNull("vente_id")->whereNotNull("client_id")->sum("montant");
        $debit = Reglement::whereNotNull("vente_id")->whereNotNull("client_id")->sum("montant");

        $credit_old = $clients->sum('credit_old');
        $debit_old = $clients->sum('debit_old');

        return view('editions.pointSolde', compact('clients', 'zones', 'credit', 'credit_old', 'debit_old', 'debit', 'reglements', 'sommeVentes'));
    }

    public function postPointSolde(Request $request)
    {
        $zone = Zone::find($request->zone);
        $client = Client::find($request->client);
        $credit = 0;
        $debit = 0;

        if ($request->client == "tous") {
            return redirect()->route('edition.solde');
        }

        ###___
        $credit = $client->reglements->where("for_dette",false)->whereNull("vente_id")->sum("montant");
        $debit = $client->reglements->whereNotNull("vente_id")->sum("montant");

        $ventesDleted = $client->_deletedVentes;

        ####____
        $reglements = $client->reglements->sum("montant");

        $ventes = [];

        if (!$request->zone && $request->client) {
            if ($request->client == "tous") {
                $ventes = Vente::join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                    ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                    // ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')

                    // SEULE LES VENTES VALIDE SONT RECUPERES
                    ->where('valide', true)

                    ->select('ventes.*', 'clients.raisonSociale', 'clients.telephone')
                    ->orderByDesc('ventes.code')
                    ->get();
            } else {
                $ventes = Vente::join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                    ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                    ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
                    ->where('clients.id', $client->id)

                    // SEULE LES VENTES VALIDE SONT RECUPERES
                    ->where('valide', true)

                    ->select('ventes.*', 'clients.raisonSociale', 'clients.telephone', 'zones.libelle as Zlibelle')
                    ->orderByDesc('ventes.code')
                    ->get();
            }
        }

        if ($request->zone && $request->client) {
            if ($request->client == "tous") {
                $ventes = Vente::join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                    ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                    ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
                    ->where('zones.id', $zone->id)

                    // SEULE LES VENTES VALIDE SONT RECUPERES
                    ->where('valide', true)

                    ->select('ventes.*', 'clients.raisonSociale', 'clients.telephone', 'zones.libelle as Zlibelle')
                    ->orderByDesc('ventes.code')
                    ->get();
            } else {
                $ventes = Vente::join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                    ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                    ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
                    ->where('clients.id', $client->id)
                    ->where('zones.id', $zone->id)

                    // SEULE LES VENTES VALIDE SONT RECUPERES
                    ->where('valide', true)

                    ->select('ventes.*', 'clients.raisonSociale', 'clients.telephone', 'zones.libelle as Zlibelle')
                    ->orderByDesc('ventes.code')
                    ->get();
            }
        }

        if ($request->zone && !$request->client) {
            $ventes = Vente::join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
                ->where('zones.id', $zone->id)

                // SEULE LES VENTES VALIDE SONT RECUPERES
                ->where('valide', true)

                ->select('ventes.*', 'clients.raisonSociale', 'clients.telephone', 'zones.libelle as Zlibelle')
                ->orderByDesc('ventes.code')
                ->get();
        }

        $_sommeVentes = $ventes->sum('montant');
        $_client = $client;
        $montant = 0;
        $regle = 0;

        $_montant = 0;
        $_regle = 0;

        foreach ($ventes as $item) {
            $_montant = $montant + $item->montant;
            $_regle = $regle + $item->reglements()->sum('montant');
        }

        #####____
        return redirect()->route('edition.solde')->withInput()->with('resultat', ['type' => 1, 'ventes' => $ventes, 'client' => $client, '_client' => $_client, 'zone' => $zone, 'credit' => $credit, 'debit' => $debit, 'reglements' => $reglements, "_sommeVentes" => $_sommeVentes, "_montant" => $_montant, "_regle" => $_regle, "ventesDleted" => $ventesDleted]);
    }

    public function etatCompte()
    {
        $clients = Client::all();
        $zones = Zone::all();
        $SommeCompte = CompteClient::all()->sum('solde');
        $reglements = Reglement::all()->sum('montant');
        $sommeVentes = Vente::all()->sum('montant');
        $credit = $clients->sum('credit');
        $debit = $clients->sum('debit');

        return view('editions.etatCompte', compact('clients', 'zones', 'credit', 'debit', 'reglements', 'SommeCompte', 'sommeVentes'));
    }

    public function postetatCompte(Request $request)
    {
        ###___
        if (!$request->zone) {
            $clients = Client::all();
            $zones = Zone::all();
            $SommeCompte = CompteClient::all()->sum('solde');
            $reglements = Reglement::all()->sum('montant');
            $sommeVentes = Vente::all()->sum('montant');
        } else {
            $zone = Zone::find($request->zone);
            $clients = Client::where('departement_id', $zone->departement_id)->get();

            $compteClients = [];
            $reglement_amonts = [];
            $vente_amonts = [];

            foreach ($clients as $key => $client) {

                ###____SOMECOMPTE
                foreach ($client->compteClients as $key => $compteClient) {
                    array_push($compteClients, $compteClient->solde);
                }

                ###_____VENTES
                foreach ($client->commandeclients as $key => $commande) {
                    $vente = $commande->vente;

                    if ($vente) {
                        array_push($vente_amonts, $vente->montant);
                    }
                }

                ###_____REGLEMENTS
                foreach ($client->commandeclients as $key => $commande) {
                    $rgls = $commande->vente->reglements;

                    if ($rgls) {
                        foreach ($rgls as $key => $reglement) {
                            array_push($reglement_amonts, $reglement->montant);
                        }
                    }
                }
            }

            ###___
            $zones = Zone::all();
            $SommeCompte = array_sum($compteClients);
            $reglements = array_sum($reglement_amonts);
            $sommeVentes = array_sum($vente_amonts);
        }

        ###____
        $credit = $clients->sum('credit');
        $debit = $clients->sum('debit');

        Session('resultat', ['type' => 1, 'clients' => $clients, 'zone' => Zone::all()]);

        // 
        return redirect()->route('edition.etatCompte')->withInput()->with('resultat', ['type' => 1, 'clients' => $clients, 'SommeCompte' => $SommeCompte, 'credit' => $credit, 'debit' => $debit]);
    }

    public function etatLivCde()
    {
        $fournisseurs = Fournisseur::all();
        return view('editions.etatlivcde', compact('fournisseurs'));
    }

    public function postEtatLivCde(Request $request)
    {
        $fournisseur = Fournisseur::find($request->fournisseur);
        $newBcs = [];
        if ($request->debut && $request->fin)
            $bcs = BonCommande::whereIn('statut', ['Valider', 'Programmer', 'Livrer'])->whereBetween('dateBon', [$request->debut, $request->fin])->get();
        else
            $bcs = BonCommande::whereIn('statut', ['Valider', 'Programmer', 'Livrer'])->get();

        ####____
        foreach ($bcs as $bc) {

            $item = new \stdClass();
            $qteBc = $bc->detailboncommandes()->sum('qteCommander');
            $montBc = $bc->montant;

            $totalValider = $bc->detailboncommandes[0]->programmations()->whereIn('statut', ['Valider', 'Livrer', 'Vendu'])->orderByDesc('id')->get();
            $_qteprogrammer = collect($totalValider)->sum('qteprogrammer'); # number_format(collect($totalValider)->sum('qteprogrammer'), 2, ",", " ");
            $_qteLiv = collect($totalValider)->sum('qtelivrer'); #number_format(collect($totalValider)->sum('qtelivrer'), 2, ",", " ");
            $_Montprog = collect($totalValider)->sum('qteprogrammer') * $bc->detailboncommandes[0]->pu; # number_format(collect($totalValider)->sum('qteprogrammer')*$bc->detailboncommandes[0]->pu, 2, ",", " ");
            $_MontLiv = collect($totalValider)->sum('qtelivrer') * $bc->detailboncommandes[0]->pu; # number_format(collect($totalValider)->sum('qtelivrer')*$bc->detailboncommandes[0]->pu, 2, ",", " ");

            $prog = Programmation::join('detail_bon_commandes', 'programmations.detail_bon_commande_id', '=', 'detail_bon_commandes.id')->where('bon_commande_id', $bc->id)->pluck('programmations.id');
            $qteVendu = Vendu::whereIn('programmation_id', $prog)->sum('qteVendu');
            $montVendu = Vendu::whereIn('programmation_id', $prog)
                ->select('qteVendu', 'pu')
                ->get()
                ->sum(function ($item) {
                    return $item->qteVendu * $item->pu;
                });

            $item->bc = $bc;
            $item->qteBc = $qteBc;
            $item->montBc = $montBc;
            $item->qteprog = $_qteprogrammer;
            $item->Montprog = $_Montprog;
            $item->qteLiv = $_qteLiv;
            $item->MontLiv = $_MontLiv;
            $item->qteVendu = $qteVendu;
            $item->montVendu = $montVendu;
            $newBcs[] = $item;
        }

        return redirect()->route('edition.etatlivraisoncde')->withInput()->with('resultat', ['type' => 1, 'bcs' => $newBcs, 'fournisseur' => $fournisseur]);
    }

    public function EtatGenePeriode()
    {
        return view('editions.etatgeneperiode');
    }

    public function postEtatGenePeriode(Request $request)
    {
        if ($request->debut && $request->fin)
            $reglements = Reglement::whereBetween('created_at', [$request->debut, $request->fin])->where('user_validateur_id', '<>', NULL)->get();
        else
            $reglements = Reglement::where('user_validateur_id', '<>', NULL)->get();

        return redirect()->route('edition.EtatGenePeriode')->withInput()->with('resultat', ['reglements' => $reglements, 'debut' => $request->debut, 'fin' => $request->fin]);
    }

    public function etatEtatVentePeriode()
    {
        $users = DB::select("
            SELECT users.id, users.name
            FROM users
            WHERE users.id in (
                SELECT user_id 
                FROM avoirs
                WHERE role_id = 3
            )
            AND super = 0
            ORDER BY name
        ");

        return view('editions.etatventeperiode', compact('users'));
    }

    public function postEtatVentePeriode(Request $request)
    {
        //Prévoir le validator
        $request->validate([
            'debut' => ['required'],
            'fin' => ['required']
        ]);
        if ($request->user == 'tout') {
            $ventes = Vente::where('ventes.statut', 'Vendue')
                ->join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                ->select('ventes.*', 'clients.raisonSociale', 'clients.telephone')
                ->whereBetween('date', [$request->debut, $request->fin])
                ->orderByDesc('ventes.code')
                ->get();
            $user = null;
        } else {
            $ventes = Vente::where('ventes.statut', 'Vendue')
                ->join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                ->select('ventes.*', 'clients.raisonSociale', 'clients.telephone')
                ->whereBetween('date', [$request->debut, $request->fin])
                ->where('ventes.users', $request->user)
                ->orderByDesc('ventes.code')
                ->get();
            count($ventes) > 0 ? $user = $ventes[0]->utilisateur : $user = null;
        }
        return redirect()->route('edition.etatventeperiode')->withInput()->with('resultat', ['ventes' => $ventes, 'user' => $user, 'debut' => $request->debut, 'fin' => $request->fin]);
    }

    public function etatZonePeriode()
    {
        return view('editions.etatzoneperiode');
    }

    public function postEtatZonePeriode(Request $request)
    {
        //Prévoir le validator
        $request->validate([
            'zone_id' => ['required'],

        ]);
        $ventes = Vente::where('ventes.statut', 'Vendue')
            ->join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
            ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
            ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
            ->select('ventes.*', 'clients.raisonSociale', 'clients.telephone', 'zones.libelle')
            ->where('commande_clients.zone_id', $request->zone_id)
            ->orderByDesc('ventes.code')
            ->get();
        return redirect()->route('edition.etatventeperiode')->withInput()->with('resultat', ['ventes' => $ventes, 'debut' => $request->debut, 'fin' => $request->fin]);
    }

    public function etatReglementPeriode(Request $request)
    {
        $reglements = Reglement::whereNotNull("vente_id")->whereNotNull("client_id")->get();
        ## QUAND C'EST UNE REQUETE GET

        if ($request->method() == "GET") {
            session()->put('result', false);
            return view('editions.etatreglementperiode', compact('reglements'));
        }

        ## QUAND C'EST UNE REQUETE POST

        $startDate = $request->get('debut');
        $endDate = $request->get('fin');
        ##___

        $reglements = $reglements->whereBetween('created_at', [$startDate, $endDate]);

        session()->put('result', true);
        return view('editions.etatreglementperiode', compact("reglements", 'startDate', 'endDate'));
    }

    public function etatReglementPeriodeRep()
    {
        $Rep = Representant::all();
        return view('editions.etatreglementperiodeRepre', compact('Rep'));
    }

    public function postEtatReglementPeriodeRep(Request $request)
    {
        //Prévoir le validator
        $Rep  = Representant::find($request->zone);
        $request->validate([
            'debut' => ['required'],
            'fin' => ['required']
        ]);

        $reglements = Reglement::join('type_detail_recus', 'type_detail_recus.id', '=', 'reglements.type_detail_recu_id')
            ->join('comptes', 'comptes.id', '=', 'reglements.compte_id')
            ->join('users', 'users.id', '=', 'reglements.user_id')
            ->join('banques', 'banques.id', '=', 'comptes.banque_id')
            ->join('ventes', 'ventes.id', '=', 'reglements.vente_id')
            ->join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
            ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
            ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
            ->select(
                'clients.raisonSociale',
                'clients.telephone',
                'zones.libelle as zone',
                'ventes.id as id_vente',
                'ventes.code as code_vente',
                'ventes.montant as montant_vente',
                'reglements.date',
                'reglements.id as id_règlement',
                'reglements.code as code_reglement',
                'reglements.montant as montant_reglement',
                'comptes.numero',
                'banques.sigle as banque',
                'users.name as recouvreur'
            )
            ->where('zones.representant_id', $Rep->id)
            ->whereBetween('reglements.date', [$request->debut, $request->fin])
            ->orderByDesc('ventes.id')
            ->get();
        $nbre = count($reglements);
        return redirect()->route('edition.etatReglementperiodeRep')->withInput()->with('resultat', ['nbre' => $nbre, 'reglements' => $reglements, 'Rep' => $Rep, 'debut' => $request->debut, 'fin' => $request->fin]);
    }

    public function postEtatReglementPeriode(Request $request)
    {
        //Prévoir le validator
        $banque = Banque::find($request->banque);
        $zone = Zone::find($request->zone);
        $request->validate([
            'debut' => ['required'],
            'fin' => ['required']
        ]);

        if ($request->banque && !$request->zone) {
            $reglements = Reglement::join('type_detail_recus', 'type_detail_recus.id', '=', 'reglements.type_detail_recu_id')
                ->join('comptes', 'comptes.id', '=', 'reglements.compte_id')
                ->join('users', 'users.id', '=', 'reglements.user_id')
                ->join('banques', 'banques.id', '=', 'comptes.banque_id')
                ->join('ventes', 'ventes.id', '=', 'reglements.vente_id')
                ->join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
                ->select(
                    'clients.raisonSociale',
                    'clients.telephone',
                    'zones.libelle as zone',
                    'ventes.id as id_vente',
                    'ventes.code as code_vente',
                    'ventes.montant as montant_vente',
                    'reglements.date',
                    'reglements.reference as reference',
                    'reglements.id as id_règlement',
                    'reglements.code as code_reglement',
                    'reglements.montant as montant_reglement',
                    'comptes.numero',
                    'banques.sigle as banque',
                    'users.name as recouvreur'
                )
                ->where('banques.id', $banque->id)
                ->whereBetween('reglements.date', [$request->debut, $request->fin])
                ->orderByDesc('ventes.id')
                ->get();
            $nbre = count($reglements);

            return redirect()->route('edition.etatReglementperiode')->withInput()->with('resultat', ['nbre' => $nbre, 'reglements' => $reglements, 'zone' => $zone, 'banque' => $banque, 'debut' => $request->debut, 'fin' => $request->fin]);
        }

        if (!$request->banque && $request->zone) {
            $reglements = Reglement::join('type_detail_recus', 'type_detail_recus.id', '=', 'reglements.type_detail_recu_id')
                ->join('comptes', 'comptes.id', '=', 'reglements.compte_id')
                ->join('users', 'users.id', '=', 'reglements.user_id')
                ->join('banques', 'banques.id', '=', 'comptes.banque_id')
                ->join('ventes', 'ventes.id', '=', 'reglements.vente_id')
                ->join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
                ->select(
                    'clients.raisonSociale',
                    'clients.telephone',
                    'zones.libelle as zone',
                    'ventes.id as id_vente',
                    'ventes.code as code_vente',
                    'ventes.montant as montant_vente',
                    'reglements.date',
                    'reglements.id as id_règlement',
                    'reglements.code as code_reglement',
                    'reglements.reference as reference',
                    'reglements.montant as montant_reglement',
                    'comptes.numero',
                    'banques.sigle as banque',
                    'users.name as recouvreur'
                )
                ->where('zones.id', $zone->id)
                ->whereBetween('reglements.date', [$request->debut, $request->fin])
                ->orderByDesc('ventes.id')
                ->get();
            $nbre = count($reglements);
            return redirect()->route('edition.etatReglementperiode')->withInput()->with('resultat', ['nbre' => $nbre, 'reglements' => $reglements, 'zone' => $zone, 'banque' => $banque, 'debut' => $request->debut, 'fin' => $request->fin]);
        }

        if ($request->banque && $request->zone) {
            $reglements = Reglement::join('type_detail_recus', 'type_detail_recus.id', '=', 'reglements.type_detail_recu_id')
                ->join('comptes', 'comptes.id', '=', 'reglements.compte_id')
                ->join('users', 'users.id', '=', 'reglements.user_id')
                ->join('banques', 'banques.id', '=', 'comptes.banque_id')
                ->join('ventes', 'ventes.id', '=', 'reglements.vente_id')
                ->join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
                ->select(
                    'clients.raisonSociale',
                    'clients.telephone',
                    'zones.libelle as zone',
                    'ventes.id as id_vente',
                    'ventes.code as code_vente',
                    'ventes.montant as montant_vente',
                    'reglements.date',
                    'reglements.id as id_règlement',
                    'reglements.code as code_reglement',
                    'reglements.reference as reference',
                    'reglements.montant as montant_reglement',
                    'comptes.numero',
                    'banques.sigle as banque',
                    'users.name as recouvreur'
                )
                ->where('banques.id', $banque->id)
                ->where('zones.id', $zone->id)
                ->whereBetween('reglements.date', [$request->debut, $request->fin])
                ->orderByDesc('ventes.id')
                ->get();
            $nbre = count($reglements);
            return redirect()->route('edition.etatReglementperiode')->withInput()->with('resultat', ['nbre' => $nbre, 'reglements' => $reglements, 'zone' => $zone, 'banque' => $banque, 'debut' => $request->debut, 'fin' => $request->fin]);
        }

        if (!$request->banque && !$request->zone) {

            $reglements = Reglement::join('type_detail_recus', 'type_detail_recus.id', '=', 'reglements.type_detail_recu_id')
                ->join('comptes', 'comptes.id', '=', 'reglements.compte_id')
                ->join('users', 'users.id', '=', 'reglements.user_id')
                ->join('banques', 'banques.id', '=', 'comptes.banque_id')
                ->join('ventes', 'ventes.id', '=', 'reglements.vente_id')
                ->join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
                ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
                ->select(
                    'clients.raisonSociale',
                    'clients.telephone',
                    'zones.libelle as zone',
                    'ventes.id as id_vente',
                    'ventes.code as code_vente',
                    'ventes.montant as montant_vente',
                    'reglements.date',
                    'reglements.id as id_règlement',
                    'reglements.code as code_reglement',
                    'reglements.reference as reference',
                    'reglements.montant as montant_reglement',
                    'comptes.numero',
                    'banques.sigle as banque',
                    'users.name as recouvreur'
                )
                ->whereBetween('reglements.date', [$request->debut, $request->fin])
                ->orderByDesc('ventes.id')
                ->get();
            $nbre = count($reglements);
            return redirect()->route('edition.etatReglementperiode')->withInput()->with('resultat', ['nbre' => $nbre, 'reglements' => $reglements, 'zone' => $zone, 'banque' => $banque, 'debut' => $request->debut, 'fin' => $request->fin]);
        }
    }

    public function etatVenteControllerPeriode()
    {
        return view('editions.etatzoneperiode');
    }

    public function postEtatVenteControllerPeriode(Request $request)
    {
        //Prévoir le validator
        $request->validate([
            'debut' => ['required'],
            'fin' => ['required']
        ]);
        $ventes = Vente::where('ventes.statut', 'Contrôller')
            ->join('commande_clients', 'ventes.commande_client_id', '=', 'commande_clients.id')
            ->join('clients', 'commande_clients.client_id', '=', 'clients.id')
            ->join('zones', 'commande_clients.zone_id', '=', 'zones.id')
            ->select('ventes.*', 'clients.raisonSociale', 'clients.telephone', 'zones.libelle')
            ->whereBetween('date', [$request->debut, $request->fin])
            ->orderByDesc('ventes.code')
            ->get();
        return redirect()->route('edition.etatventeperiode')->withInput()->with('resultat', ['ventes' => $ventes, 'debut' => $request->debut, 'fin' => $request->fin]);
    }

    public function etatCaProgPeriode()
    {
        return view('editions.etatCaProgPeriode');
    }

    public function postEtatCaProgPeriode(Request $request)
    {
        //Prévoir le validator
        $request->validate([
            'debut' => ['required'],
            'fin' => ['required']
        ]);
        if (!$request->charger) {
            $programmations = Programmation::Join('camions', 'camions.id', '=', 'programmations.camion_id')
                ->join('chauffeurs', 'chauffeurs.id', '=', 'programmations.avaliseur_id')
                ->join('zones', 'programmations.zone_id', '=', 'zones.id')
                ->join('avaliseurs', 'programmations.avaliseur_id', '=', 'avaliseurs.id')
                ->select(
                    'programmations.*',
                    'camions.immatriculationTracteur as camion',
                    'avaliseurs.nom as nom_avaliseur',
                    'avaliseurs.prenom as prenom_avaliseur',
                    'chauffeurs.nom as nom_chauffeur',
                    'chauffeurs.prenom as prenom_chauffeur',
                    'zones.libelle'
                )
                ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                ->orderByDesc('programmations.code')
                ->get();
            return redirect()->route('edition.etatCaProgPeriode')->withInput()->with('resultat', ['programmations' => $programmations, 'debut' => $request->debut, 'fin' => $request->fin]);
        }
        if ($request->charger == 1) {
            $programmations = Programmation::Join('camions', 'camions.id', '=', 'programmations.camion_id')
                ->join('chauffeurs', 'chauffeurs.id', '=', 'programmations.avaliseur_id')
                ->join('zones', 'programmations.zone_id', '=', 'zones.id')
                ->join('avaliseurs', 'programmations.avaliseur_id', '=', 'avaliseurs.id')
                ->select(
                    'programmations.*',
                    'camions.immatriculationTracteur as camion',
                    'avaliseurs.nom as nom_avaliseur',
                    'avaliseurs.prenom as prenom_avaliseur',
                    'chauffeurs.nom as nom_chauffeur',
                    'chauffeurs.prenom as prenom_chauffeur',
                    'zones.libelle'
                )
                ->where('dateSortie', NULL)
                ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                ->orderByDesc('programmations.code')
                ->get();
            return redirect()->route('edition.etatCaProgPeriode')->withInput()->with('resultat', ['programmations' => $programmations, 'debut' => $request->debut, 'fin' => $request->fin]);
        }
        if ($request->charger == 2) {
            $programmations = Programmation::Join('camions', 'camions.id', '=', 'programmations.camion_id')
                ->join('chauffeurs', 'chauffeurs.id', '=', 'programmations.avaliseur_id')
                ->join('zones', 'programmations.zone_id', '=', 'zones.id')
                ->join('avaliseurs', 'programmations.avaliseur_id', '=', 'avaliseurs.id')
                ->select(
                    'programmations.*',
                    'camions.immatriculationTracteur as camion',
                    'avaliseurs.nom as nom_avaliseur',
                    'avaliseurs.prenom as prenom_avaliseur',
                    'chauffeurs.nom as nom_chauffeur',
                    'chauffeurs.prenom as prenom_chauffeur',
                    'zones.libelle'
                )
                ->where('dateSortie', '<>', NULL)
                ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                ->orderByDesc('programmations.code')
                ->get();
            return redirect()->route('edition.etatCaProgPeriode')->withInput()->with('resultat', ['programmations' => $programmations, 'debut' => $request->debut, 'fin' => $request->fin, 'charger' => $request->charger]);
        }
    }

    public function etatLivraisonPeriode()
    {
        return view('editions.etatLivraisonperiode');
    }

    public function postEtatLivraisonPeriode(Request $request)
    {
        //Prévoir le validator
        $request->validate([
            'debut' => ['required'],
            'fin' => ['required']
        ]);
        $programmations = Programmation::where('programmations.statut', 'Livrer')->where('imprimer', '1')
            ->join('detail_bon_commandes', 'programmations.detail_bon_commande_id', '=', 'detail_bon_commandes.id')
            ->join('bon_commandes', 'detail_bon_commandes.bon_commande_id', '=', 'bon_commandes.id')
            ->select('programmations.*', 'bon_commandes.code as CodeBCDE', 'detail_bon_commandes.*')
            ->whereBetween('datelivrer', [$request->debut, $request->fin])
            ->orderByDesc('code')
            ->get();
        return redirect()->route('edition.etatLivraisonPeriode')->withInput()->with('resultat', ['programmations' => $programmations, 'debut' => $request->debut, 'fin' => $request->fin]);
    }

    public function creditARecouvrir()
    {
        $ventes = Vente::where('statut', 'Vendue')->where('type_vente_id', 2)->orderByDesc('code')->get();
        return view('editions.recouvrement', compact('ventes'));
    }

    public function VenteCamion()
    {
        $ventes = Vente::all();
        return view('editions.venteCamion', compact('ventes'));
    }

    public function postVenteCamion(Request $request)
    {
        $ventes = Vente::find($request->id);
        $ventesCa = DB::select("
        SELECT
          `camions`.`immatriculationTracteur`,
          `chauffeurs`.`nom`,
          `chauffeurs`.`prenom`,
          `chauffeurs`.`telephone`
        FROM `ventes`
        INNER JOIN `vendus` ON `vendus`.`vente_id` = `ventes`.`id`
        INNER JOIN `programmations` ON `programmations`.`id` = `vendus`.`programmation_id`
        INNER JOIN `chauffeurs` ON `chauffeurs`.`id` = `programmations`.`chauffeur_id`
        INNER JOIN `camions` ON camions.id = `programmations`.`camion_id`
        WHERE `ventes`.`id` = ? ", [$request->id]);

        return redirect()->route('edition.VenteCamion')->withInput()->with('resultat', ['type' => 1, 'ventesCa' => $ventesCa, 'ventes' => $ventes]);
    }

    // GESTION DE LA LISTE DES APPROVISIONNEMENTS
    function CompteApprovisionnement(Request $request)
    {
        ## QUAND C'EST UNE REQUETE GET
        if ($request->method() == "GET") {
            $reglements = Reglement::whereNull("vente_id")->whereNotNull("client_id")->orderBy('id', 'desc')->get();

            session()->put('result', false);
            return view('editions.approvisionnementCompte', compact('reglements'));
        }

        ## QUAND C'EST UNE REQUETE POST
        $startDate = $request->get('debut');
        $endDate = $request->get('fin');
        ##___

        $reglements = Reglement::whereNull("vente_id")->whereNotNull("client_id")->orderBy('id', 'desc')->whereBetween('created_at', [$startDate, $endDate])->get();

        session()->put('result', true);
        return view('editions.approvisionnementCompte', compact('reglements', 'startDate', 'endDate'));
    }

    // RESTORER LES VENTES SUPPRIMEES AU SOLDE DU CLIENT
    function RestoreVenteDeleted(Request $request, DeletedVente $vente, Client $client)
    {
        if ($vente->restituted) {
            return back()->with("error", "Vente déjà restituée");
        }

        if (!$client) {
            return back()->with("error", "Ce client n'existe pas");
        }

        ###___
        $compteClient = $client->compteClients->first();
        $venteAmount =  $vente->montant;

        $client = $compteClient->client;
        $client->credit = $client->credit + $venteAmount;
        $client->debit = $client->debit - $venteAmount;
        $client->save();

        ###___
        $compteClient->solde = $client->credit + $client->debit;
        $compteClient->save();
        ###____

        ###___
        $vente->restituted = true;
        $vente->restitutor = auth()->user()->id;
        $vente->save();

        ###___
        return back()->with("message", "Montant de vente restitué avec succès!");
    }
}
