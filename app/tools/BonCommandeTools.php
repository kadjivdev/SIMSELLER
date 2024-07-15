<?php

namespace App\tools;

use App\Models\BonCommande;
use App\Models\DetailBonCommande;
use Illuminate\Support\Facades\DB;

class BonCommandeTools
{
    /**
     * @param BonCommande $bonCommande
     * @return void
     * Cette fonction ajuste les produits vendus par un fournisseur sur un bon
     * de commande
     */
    public static function ajusterProduitFournisseur(BonCommande $bonCommande){
        DB::delete("
            DELETE detail_bon_commandes
            FROM detail_bon_commandes,bon_commandes
            WHERE bon_commandes.id = detail_bon_commandes.bon_commande_id
            AND detail_bon_commandes.bon_commande_id  = ?
            AND produit_id not in (SELECT commercialisers.produit_id
            FROM commercialisers
            WHERE fournisseur_id = ?)
        ",[$bonCommande->id,$bonCommande->fournisseur_id]);
    }

    /**
     * @param BonCommande $bonCommande
     * @return void
     * Cette fonction recale le total d'un bon de commande
     * et faire la mise à jour
     */
    public static function calculerTotalCommande(BonCommande $bonCommande){
        $detailboncommandes = DetailBonCommande::where('bon_commande_id', $bonCommande->id)->get();
        $somme = 0;
        foreach($detailboncommandes as $detailboncommande){
            $montants = ($detailboncommande->qteCommander * $detailboncommande->pu)-$detailboncommande->remise;
            $somme += $montants;

        }
        $bonCommande->montant = $somme;
        $bonCommande->update();
    }


    public static function statutUpdate(BonCommande $boncommande, $statut){
        $boncommande->statut = $statut;
        $boncommande->update();
    }
}
