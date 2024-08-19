<?php

namespace App\Http\Controllers;

use App\Mail\SuspectMail;
use App\Rules\CheckQuanteCde;
use App\Rules\CheckQuantite;
use App\Rules\CheckStock;
use App\Rules\VenduDoublonProduitStokValide;
use App\tools\CommandeClientTools;
use Exception;
use App\Models\Vendu;
use App\Models\Vente;
use App\Models\Produit;
use App\Models\Commander;
use Illuminate\Http\Request;
use App\Models\Programmation;
use App\Models\CommandeClient;
use App\Models\DetailBonCommande;
use App\Models\UpdateVente;
use App\tools\ControlesTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class VenduController extends Controller
{
    public function __construct()
    {
        $this->middleware('vendeur')->only(['create', 'store', 'update', 'delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create(Vente $vente,  Vendu $vendu = NULL)
    {
        // dd("gogo");
        $ver = Vente::where('id', $vente->id)->first();
        $commandeclient = CommandeClient::findOrFail($vente->commande_client_id);
        if ($ver == NULL) {
            $commanders = Commander::where('commande_client_id', $commandeclient->id)->pluck('produit_id');
            $produits = Produit::whereIn('id', $commanders)->get();
        } else {
            $progammation = Programmation::where('statut', 'Livrer')->pluck('detail_bon_commande_id');
            $detailboncommande = DetailBonCommande::whereIn('id', $progammation)->pluck('produit_id');
            $produits = Produit::whereIn('id', $detailboncommande)->get();
        }

        $qteTotal = $vente->vendus()->sum('qteVendu');
        $totalVendu = 0;

        foreach ($commandeclient->ventes as $v) {
            if ($v->statut == 'Vendue')
                $totalVendu +=  $v->qteTotal;
        }

        ####____
        $bl = NULL;

        foreach ($vente->vendus as $vendu) {
            $_bl = $vendu->programmation->bl_gest ? $vendu->programmation->bl_gest : $vendu->programmation->bl;
            // dd($vendu->programmation);
            if ($_bl) {
                $bl = $_bl;
            }
        }

        return view('vendus.create', compact('vente', 'vendu', 'produits', 'qteTotal', 'totalVendu', "bl"));
    }

    public function store(Request $request, Vente $vente, Vendu $vendu = NULL)
    {
        try {
            $ver = Vente::where('id', $vente->id)->where('code', 'LIKE', 'VD%')->first();
            if ($vendu) {
                if ($request->remise == NULL) {
                    $validator = Validator::make($request->all(), [
                        'produit_id' => ['required'],
                        'programmation_id' => ['required'],
                        'qteVendu' => ['required'],
                        'pu' => ['required'],
                    ]);

                    if ($vente->transport) {
                        $validator = Validator::make($request->all(), [
                            'produit_id' => ['required'],
                            'programmation_id' => ['required'],
                            'qteVendu' => ['required'],
                            'pu' => ['required'],
                            'transport' => ['required'],
                        ]);
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'produit_id' => ['required'],
                        'programmation_id' => ['required'],
                        'qteVendu' => ['required'],
                        'pu' => ['required'],
                        'remise' => ['required'],
                    ]);

                    if ($vente->transport) {
                        $validator = Validator::make($request->all(), [
                            'produit_id' => ['required'],
                            'programmation_id' => ['required'],
                            'qteVendu' => ['required'],
                            'pu' => ['required'],
                            'transport' => ['required'],
                        ]);
                    }
                }

                if ($validator->fails()) {
                    return redirect()->route('vendus.create', ['vente' => $vente->id, 'vendu' => $vendu->id])->withErrors($validator->errors())->withInput();
                }


                $vendus = $vendu->update([
                    'vente_id' => $vente->id,
                    'programmation_id' => $request->programmation_id,
                    'qteVendu' => $request->qteVendu,
                    'pu' => $request->pu,
                    'remise' => $request->remise,
                    'users' => Auth::user()->id,
                ]);

                if ($vendus) {

                    $vendus = $vendu->where('vente_id', $vente->id)->get();
                    $somme = 0;
                    foreach ($vendus as $vendu) {
                        $montants = ($vendu->qteVendu * $vendu->pu) - $vendu->remise;
                        $somme += $montants;
                    }
                    $vente->montant = $somme + ($request->qteVendu * $request->transport);
                    $ventes = $vente->update();

                    if ($ventes) {

                        if ($ver) {
                            $commander = Commander::findOrFail($vente->commande_client_id);
                            $commanders = $commander->update([
                                'commande_client_id' => $vente->commande_client_id,
                                'produit_id' => $request->produit_id,
                                'qteCommander' => $request->qteVendu,
                                'pu' => $request->pu,
                                'remise' => $request->remise,
                                'users' => Auth::user()->id,
                            ]);


                            if ($commanders) {

                                $commanders = $commander->where('commande_client_id', $vente->commande_client_id)->get();
                                $montants = null;
                                $somme = 0;
                                foreach ($vendus as $vendu) {
                                    $montants = ($vendu->qteVendu * $vendu->pu) - $vendu->remise;
                                    $somme += $montants;
                                }
                                
                                $vente->montant = $somme + ($request->qteVendu * $request->transport);
                                $vente->update();

                                $commandeclient = CommandeClient::findOrFail($vente->commande_client_id);
                                $commandeclient->montant = $somme;
                                $commandeclients = $commandeclient->update();

                                if ($commandeclients) {
                                    Session()->flash('message', 'Produit vendu modifié succès!');
                                    return redirect()->route('vendus.create', ['vente' => $vente->id, 'vendu' => $vendu->id]);
                                }
                            }
                        }
                    }
                }
            } else {
                // dd("gogo");
                if ($request->remise == NULL) {
                    $validator = Validator::make($request->all(), [
                        'produit_id' => ['required'],
                        'programmation_id' => ['required', new VenduDoublonProduitStokValide($request->programmation_id, $vente->id)],
                        'qteVendu' => ['required', new CheckStock($request->programmation_id), new CheckQuantite($vente)],
                        'pu' => ['required'],
                        'qteTotal' => ['required', new CheckQuanteCde($vente)]
                    ]);

                    if ($vente->transport) {
                        $validator = Validator::make($request->all(), [
                            'produit_id' => ['required'],
                            'programmation_id' => ['required', new VenduDoublonProduitStokValide($request->programmation_id, $vente->id)],
                            'qteVendu' => ['required', new CheckStock($request->programmation_id), new CheckQuantite($vente)],
                            'pu' => ['required'],
                            'qteTotal' => ['required', new CheckQuanteCde($vente)],
                            'transport' => ['required']
                        ]);
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'produit_id' => ['required'],
                        'programmation_id' => ['required', new VenduDoublonProduitStokValide($request->programmation_id, $vente->id)],
                        'qteVendu' => ['required', new CheckStock($request->programmation_id), new CheckQuantite($vente)],
                        'pu' => ['required'],
                        'remise' => ['required'],
                        'qteTotal' => ['required', new CheckQuanteCde($vente)],
                    ]);
                    if ($vente->transport) {
                        $validator = Validator::make($request->all(), [
                            'produit_id' => ['required'],
                            'programmation_id' => ['required', new VenduDoublonProduitStokValide($request->programmation_id, $vente->id)],
                            'qteVendu' => ['required', new CheckStock($request->programmation_id), new CheckQuantite($vente)],
                            'pu' => ['required'],
                            'qteTotal' => ['required', new CheckQuanteCde($vente)],
                            'transport' => ['required']
                        ]);
                    }
                }

                if ($validator->fails()) {
                    return redirect()->route('vendus.create', ['vente' => $vente->id])->withErrors($validator->errors())->withInput();
                }

                DB::beginTransaction();
                if (!$vente->montant) {
                    $vente->update([
                        'remise' => $request->remise,
                        'pu' => $request->pu,
                        'qteTotal' => $request->qteTotal,
                        'montant' => $request->montant,
                        'produit_id' => $request->produit_id,
                        'transport' => $request->transport,
                        'destination' => $request->destination
                    ]);
                }
                $vendu = Vendu::create([
                    'vente_id' => $vente->id,
                    'programmation_id' => $request->programmation_id,
                    'qteVendu' => $request->qteVendu,
                    'pu' => $request->pu,
                    'remise' => $request->remise,
                    'users' => Auth::user()->id,
                ]);

                if ($vendu) {

                    $vendus = $vendu->where('vente_id', $vente->id)->get();
                    $montants = null;
                    $somme = 0;
                    foreach ($vendus as $vendu) {
                        $montants = ($vendu->qteVendu * $vendu->pu) - $vendu->remise;
                        $somme += $montants;
                    }


                    $programmation = Programmation::find($request->programmation_id);
                    $vente->prix_Usine = $programmation->detailboncommande->pu;
                    $vente->montant = $somme + ($request->qteVendu * $request->transport);
                    $vente->update();
                    //Les programmation Conserner par une Ventes 
                    foreach ($vendus as $key => $vendu) {
                        $programmation = Programmation::find($vendu->programmation_id);
                    }
                    if ($ver) {
                        $commander = Commander::create([
                            'commande_client_id' => $vente->commande_client_id,
                            'produit_id' => $request->produit_id,
                            'qteCommander' => $request->qteVendu,
                            'pu' => $request->pu,
                            'remise' => $request->remise,
                            'users' => Auth::user()->id,
                        ]);

                        if ($commander) {

                            $commanders = $commander->where('commande_client_id', $vente->commande_client_id)->get();
                            $montants = null;
                            $somme = 0;
                            foreach ($commanders as $commander) {
                                $montants = ($commander->qteCommander * $commander->pu) - $commander->remise;
                                $somme += $montants;
                            }

                            $commandeclient = CommandeClient::findOrFail($vente->commande_client_id);
                            $commandeclient->montant = $somme;
                            $commandeclients = $commandeclient->update();

                            if ($commandeclients) {
                                Session()->flash('message', 'Produit vendu ajouté succès!');
                                return redirect()->route('vendus.create', ['vente' => $vente->id]);
                            }
                        }
                    } else {
                        Session()->flash('message', 'Produit vendu ajouté succès!');
                        return redirect()->route('vendus.create', ['vente' => $vente->id]);
                    }
                }
                DB::commit();
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
     * @param  \App\Models\Vendu  $vendu
     * @return \Illuminate\Http\Response
     */
    public function show(Vendu $vendu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendu  $vendu
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendu $vendu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendu  $vendu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendu $vendu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendu  $vendu
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy(Vendu $vendu)
    {
        $vente = $vendu->vente;
        if ($vente->statut == "Vendue") {
            Session()->flash('error', 'Vous essayez de supprimer une ligne de vente n° ' . $vente->code . 'déjà valider. Ce comportement est suspect et sera notifier au administrateur');
            $message = "Sur la vente n° " . $vente->code . ", le vendeur " . Auth::user()->name . " a esayer de supprimer un detail alors que la vente a été déjà valider";
            $mail = new SuspectMail(['email' => env('ADMIN_SUSPECT')], 'Action suspecte', $message);
            Mail::send($mail);
            return redirect()->route('ventes.index', ['vente' => $vente->id]);
        }
        $vente->update(['montant' => $vente->montant - ($vendu->qteVendu * $vendu->pu)]);
        ControlesTools::generateLog($vendu, 'vendu', 'Suppression ligne');

        $vendu->delete();
        return redirect()->route('vendus.create', ['vente' => $vente->id])->with('msgSuppression', 'Element supprimé avec succès');
    }
}
