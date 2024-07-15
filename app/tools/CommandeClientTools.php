<?php

namespace App\tools;

use App\Models\CommandeClient;
use App\Models\Commander;
use Illuminate\Support\Facades\DB;

class CommandeClientTools
{
    public static function viderProduitCommander(CommandeClient $commandeclient){
        DB::delete('delete commanders where commanders.commande_client_id = ?', [$commandeclient->id]);
    }


    
    public static function calculerTotalCommande(CommandeClient $commandeclient){
        $commandeclient->montant = 0;
        $commandeclient->update();
    }


    public static function statutUpdate(CommandeClient $commandeclient, $statut){
        $commandeclient->statut = $statut;
        $commandeclient->update();
    }

    public static function getSuiviStock(CommandeClient $client){
        $suivisStocks = [];
        $detailCmdes = $client->commanders;
        foreach ($detailCmdes as $cmde){
            $suivisStocks[$cmde->produit_id] = [
                'id'=>$cmde->produit_id,
                'qtecde'=>$cmde->qteCommander,
                'qteLiv'=>0,
                'resteLiv'=>0
            ];
        }
        foreach($client->ventes as $vente){
            foreach ($suivisStocks as $key=> $suivisStock){
                $suivisStocks[$key]['qteLiv'] += self::calculeStockLivre($vente->id,$key);
                $suivisStocks[$key]['resteLiv'] = $suivisStocks[$key]['qtecde']-$suivisStocks[$key]['qteLiv'];
            }
        }
        return $suivisStocks;
    }

    public static function calculeStockLivre($vente_id,$produit_id){
        $stockVente = DB::select("
            SELECT SUM(vendus.qteVendu) AS totalVente
            FROM vendus
            INNER JOIN programmations ON vendus.programmation_id = programmations.id
            INNER JOIN detail_bon_commandes ON programmations.detail_bon_commande_id = detail_bon_commandes.id
            INNER JOIN produits ON detail_bon_commandes.produit_id = produits.id
            INNER JOIN ventes ON vendus.vente_id = ventes.id
            WHERE vendus.vente_id = ?
            AND produits.id = ?
            AND ventes.statut = 'Vendue'
        ",[$vente_id,$produit_id]);

        return $stockVente[0]->totalVente;
    }
    public static function changeStatutCommande(CommandeClient $client){
        $suivis = self::getSuiviStock($client);
        foreach ($suivis as $suivi){
            if($suivi['resteLiv'] == 0){
                $statut = 'Livrée';
            }
            elseif($suivi['qteLiv'] > 0){
                $statut = 'Livraison partielle';
                break;
            }
            else {
                $statut = 'Validée';
            }
        }		 if(count($suivis) == 0)            abort(412);
        $client->update(['statut'=>$statut]);
    }
}
