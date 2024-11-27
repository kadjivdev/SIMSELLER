<!-- REGULATION -->
 <?php

use App\Models\Programmation;
use Illuminate\Support\Facades\Route;

Route::get('/regulation', function () {
//     $clients = Client::all();

    // $mvts_to_delete = Mouvement::where("libelleMvt","Approvisionnement pour reglement d'ancienne dette")->get();
    // foreach ($mvts_to_delete as $mvt) {
    //     $mvt->delete();
    // }

    ####### GESTION DES REGLEMENTS QUI N'ONT PAS DE MOUVEMENTS
    // $reglements = Reglement::whereNull("vente_id")->where("for_dette", true)->orderBy("id", "desc")->get();

    // $data = [];
    // foreach ($reglements as $reglement) {
    //     if (count($reglement->_mouvements) == 0) {
    //         $data[] = $reglement;

    //         Mouvement::create([
    //             'libelleMvt' => "Approvisionnement pour reglement d'ancienne dette",
    //             'dateMvt' => $reglement->date,
    //             'montantMvt' => $reglement->montant,
    //             // 'compteClient_id' => $client->compteClients[0]->id,
    //             'compteClient_id' => null,
    //             'reglement_id' => $reglement->id,
    //             'sens' => 0,
    //             'user_id' => $reglement->user_id
    //         ]);
    //     }
    // }


    #####____REGULATION DES STOCKS
    // $programmations = Programmation::where("statut", "<>", "Annuler")->get();

    // foreach ($programmations as $programmation) {
    //     $qteProg = $programmation->qteprogrammer;
    //     $qteVendu = $programmation->vendus->sum("qteVendu");

    //     if ($qteProg == $qteVendu) {
    //         $programmation->qtelivrer = $programmation->qteprogrammer;
    //         $programmation->update();
    //     }
    // }

    #### SUPPRESSION DE TOUS LES REGLEMENTS
    // $reglements = Reglement::all();
    // foreach ($reglements as $reglement) {
    //     $reglement->delete();
    // }

    // #### SUPPRESSION DE TOUS LES MOUVEMENTS
    // $mouvements = Mouvement::all();
    // foreach ($mouvements as $mouvement) {
    //     $mouvement->delete();
    // }

    ##### CHANGEMENT DES STATUS *Contrôller* DES VENTES EN STATUT *Vendue*
    // $ventes = Vente::where("statut", "Contrôller")->get();#### count initial : 11
    // foreach ($ventes as $vente) {
    //     $vente->statut = "Vendue";
    //     $vente->update();
    // }

    ######======= REGULATION DES CREDITS DES CLIENTS
    // foreach ($clients as $clt) {
    //     ####____LE CREDIT DU CLIENT REVIENT A LA SOMME DES APPROVISIONNEMENTS (reglements sans vente_id && non pour remboursement de dette) EFFECTUES SUR SON COMPTE
    //     $clt->credit = $clt->reglements->where("for_dette",false)->whereNull("vente_id")->sum("montant");
    //     $clt->save();
    // }

    #######======== REGULATION DES DEBITS DES CLIENTS
    // foreach ($clients as $clt) {
    //     // LE DEBIT DU CLIENT REVIENT A LA SOMME DES REGLEMENTS EFFECTUES SUR SON COMPTE
    //     $clt->debit = $clt->reglements->whereNotNull("vente_id")->sum("montant");
    //     $clt->save();
    // }

    #####===== ATTACHEMENT DE CHAQUE REGLEMENT A SON CLIENT
    // $mouvements = Mouvement::all();
    // $no_clients_mvt = [];
    // $no_reglements = [];

    // foreach ($mouvements as $mvt) {
    //     $reglement = $mvt->_Reglement;

    //     if ($reglement) {
    //         if (!$mvt->compteClient->client) {
    //             $no_clients_mvt[] = $mvt;
    //         } else {
    //             $reglement->client_id = $mvt->compteClient->client->id;
    //             $reglement->save();
    //         }
    //     } else {
    //         $no_reglements[] = $mvt;
    //     }
    // }

    ####____REPORT DES ANCIENNES DETTES DES CLIENTS
    // $oldClients = ClientOld::all();
    // foreach ($oldClients as $oldClient) {
    //     $client = Client::where(["raisonSociale" => $oldClient->nomUP])->first();
    //     if ($client) {
    //         $client->credit_old = $oldClient->creditUP;
    //         $client->debit_old = $oldClient->debitUP;
    //         $client->save();
    //     }
    // }

    ####______APPROVISIONNEMENT SUR COMPTE DES CLIENTS POUR REMBOURSEMENT DES DETTES ANCIENNES
    // $reglements = DetteReglement::all();

    // foreach ($reglements as $key => $reglement) {
    //     $format = env('FORMAT_REGLEMENT');
    //     $parametre = Parametre::where('id', env('REGLEMENT'))->first();
    //     $code = $format . str_pad($parametre->valeur, 6, "0", STR_PAD_LEFT);

    //     // DEBUT APPROVISIONNEMENT

    //     $__reglement =  Reglement::create([
    //         'code' => $code,
    //         'reference' => strtoupper($reglement->reference),
    //         'date' => $reglement->date,
    //         'montant' => $reglement->montant,
    //         'document' => $reglement->document,
    //         'vente_id' => null,
    //         'compte_id' => $reglement->compte,
    //         'type_detail_recu_id' => $reglement->type_detail_recu,
    //         'user_id' => auth()->user()->id,
    //         // 'client_id' => $client->id,
    //         'clt' => $reglement->client,
    //         'for_dette' => true,
    //     ]);

    //     if ($__reglement) {

    //             $valeur = $parametre->valeur;

    //             $valeur = $valeur + 1;

    //             $parametres = Parametre::find(env('REGLEMENT'));

    //             $parametre = $parametres->update([
    //                 'valeur' => $valeur,
    //             ]);

    //             // Ici aussi on fait la même chose. On ajoute pas directement
    //             // le mouvement au compte du client
    //             //  on le fait plutôt après validation de l'approvisionnement associé à ce mouvement

    //             if ($parametre) {
    //                 Mouvement::create([
    //                     'libelleMvt' => "Approvisionnement pour reglement d'ancienne dette",
    //                     'dateMvt' => $reglement->date,
    //                     'montantMvt' => $reglement->montant,
    //                     // 'compteClient_id' => $client->compteClients[0]->id,
    //                     'compteClient_id' => null,
    //                     'reglement_id' => $reglement->id,
    //                     'sens' => 0,
    //                     'user_id' => auth()->user()->id
    //                 ]);
    //             }
    //         }
    // }

    return "Opération effectuée avec succès!";
});

#### ALGO POUR EQUILIBRER LES QTE PROGRAMMER & QTE LIVRER
// $programmations = Programmation::whereNotNull("qtelivrer")->orderBy("id","desc")->get();
        // $data = [];

        // foreach ($programmations as $programmation) {
        //     if (($programmation->qteprogrammer!=$programmation->qtelivrer) && ($programmation->qteprogrammer >= $programmation->vendus->sum("qteVendu")) ) {
        //         $data[]=[
        //             "qteprogrammer"=>$programmation->qteprogrammer,
        //             "qtelivrer"=>$programmation->qtelivrer,
        //             "qteVendu"=>$programmation->vendus->sum("qteVendu"),
        //         ];

        //         $programmation->update(["qtelivrer"=>$programmation->qteprogrammer]);
        //     }
        // }