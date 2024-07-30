<?php

namespace App\Exports;

use App\Models\ExcelReturn;
use App\Models\Vente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComptabiliteReExport implements FromCollection, WithHeadings
{ 
    public $debut,$fin,$filtre;
    public function __construct($debut, $fin,$filtre) {
        $this->debut = $debut;	
        $this->fin = $fin;
        $this->filtre = $filtre;	
    }
    public function headings(): array
    {
        return [
           'Heure & Date système',
        //    'Date système',
           'Date vente',
           'Client',
           'IFU',
           'clientFilleuls',
           'clientFilleulsIfu',
           'Date achat',
           'Produit',
           'Quantité',
           'PVR',
           'Prix TTC',
           'Prix HT',
           'Prix 1.18',
           'Net HT',
           'TVA',
           'AIB',
           'TTC',
           'FRS'
          
        ];
    }
    public function styles(): array
    {
        return [
            '1' => ['font' => ['bold']], // Applique le gras à la deuxième ligne (index 1)
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        $comptabiliser =[];
        if($this->filtre == 'on'){
            $comptabiliser =  DB::select(
                "SELECT  
                    -- export_comptabilite.`heureSysteme`,
                    -- export_comptabilite.`dateSysteme`,
                    export_comptabilite.`code`,
                    export_comptabilite.`id`, 
                    export_comptabilite.`dateVente`, 
                    export_comptabilite.`clients`, 
                    export_comptabilite.`ifu`, 
                    export_comptabilite.dateAchat,  
                    export_comptabilite.produit,
                    export_comptabilite.qte,  
                    export_comptabilite.pvr,
                    export_comptabilite.prixTTC, 
                    export_comptabilite.PrixHT,
                    export_comptabilite.`filleuls`,
                    export_comptabilite.PrixBruite,
                    export_comptabilite.NetHT,
                    export_comptabilite.TVA, 
                    export_comptabilite.AIB, 
                    export_comptabilite.TTC, 
                    export_comptabilite.FRS
                
                    FROM `export_comptabilite`  
                        WHERE  `export_comptabilite`.`date_comptabilisation` BETWEEN ? AND ?
                        ORDER BY `export_comptabilite`.`date_traitement`DESC;

            ",[$this->debut, $this->fin]);
        }else {
            
            $comptabiliser =  DB::select(
                "SELECT  
                    -- export_comptabilite.`heureSysteme`, 
                    -- export_comptabilite.`dateSysteme`,
                    export_comptabilite.`code`,
                    export_comptabilite.`id`, 
                    export_comptabilite.`dateVente`, 
                    export_comptabilite.`clients`, 
                    export_comptabilite.`ifu`, 
                    export_comptabilite.dateAchat,  
                    export_comptabilite.produit,
                    export_comptabilite.qte,  
                    export_comptabilite.pvr,
                    export_comptabilite.prixTTC, 
                    export_comptabilite.PrixHT,
                    export_comptabilite.`filleuls`,
                    export_comptabilite.PrixBruite,
                    export_comptabilite.NetHT,
                    export_comptabilite.TVA, 
                    export_comptabilite.AIB, 
                    export_comptabilite.TTC, 
                    export_comptabilite.FRS
                
                    FROM `export_comptabilite`  
                        WHERE  `export_comptabilite`.`dateCreate` BETWEEN ? AND ?
                        AND  `export_comptabilite`.`date_traitement` IS NOT NULL
                        AND  `export_comptabilite`.`date_comptabilisation` IS NOT NULL
                        ORDER BY `export_comptabilite`.`date_traitement`DESC;
                        
            ",[$this->debut, $this->fin]);
        }
       /*  $comptabiliser =  DB::select("SELECT                
        export_comptabilite.`heureSysteme`, export_comptabilite.`dateSysteme`,export_comptabilite.`code`,export_comptabilite.`id`, export_comptabilite.`dateVente`, 
        export_comptabilite.`clients`, export_comptabilite.`ifu`, export_comptabilite.dateAchat,  export_comptabilite.produit,
        export_comptabilite.qte,  export_comptabilite.pvr,export_comptabilite.prixTTC, 
        export_comptabilite.PrixHT,export_comptabilite.`filleuls`,export_comptabilite.PrixBruite,export_comptabilite.NetHT,
        export_comptabilite.TVA, export_comptabilite.AIB, export_comptabilite.TTC, export_comptabilite.FRS
         FROM `export_comptabilite`  
                WHERE `export_comptabilite`.`dateCreate` BETWEEN ? AND ?
                AND  `export_comptabilite`.`date_traitement` IS NOT NULL
                AND  `export_comptabilite`.`date_comptabilisation` IS NOT NULL;
        ",[$this->debut, $this->fin]); */
        $comptableCorrect =[];
        
        foreach ($comptabiliser as $key => $comptabilise) {
            
            if ($comptabilise->filleuls!== null) {
                $compta = json_decode($comptabilise->filleuls);
                $Export = new ExcelReturn();
                $Export->heureSysteme = GetVenteTraitedDateViaCode(venteCode: $comptabilise->code) ? GetVenteTraitedDateViaCode(venteCode: $comptabilise->code) : "---";
                // $Export->heureSysteme = $comptabilise->heureSysteme;
                // $Export->dateSysteme = date_format(date_create($comptabilise->dateSysteme),'d/m/Y');
                $Export->dateVente = $comptabilise->dateVente;
                $Export->clients = $comptabilise->clients;
                $Export->ifu = $comptabilise->ifu;
                $Export->clientFilleuls = $compta->nomPrenom; // Nouvelle colonne
                $Export->clientFilleulsifu =  $compta->ifu; // Nouvelle colonne
                $Export->dateAchat = $comptabilise->dateAchat;
                $Export->produit = $comptabilise->produit;
                $Export->qte = $comptabilise->qte;
                $Export->pvr = $comptabilise->pvr;
                $Export->prixImpot = $comptabilise->prixTTC;
                $Export->prixImpotHt = $comptabilise->PrixHT;
                $Export->prix1_18 = $comptabilise->PrixBruite;
                $Export->NETHT = $comptabilise->NetHT;
                $Export->TVA = $comptabilise->TVA;
                $Export->AIB = $comptabilise->AIB;
                $Export->TTC = $comptabilise->TTC;
                $Export->FRS = $comptabilise->FRS;
                $comptableCorrect[$key] = $Export;
                $vente = Vente::find($comptabilise->id);
                $historique =[]; 
                if($vente->comptabiliser_history){
                    $historique = json_decode($vente->comptabiliser_history);
                }
                $historique[] = [
                    'user' => Auth::user()->id,
                    'date' => date('Y-m-d H:i'),
                    'type' => 'ReExporter',
                ];
                $historique = json_encode($historique);
                $vente->date_comptabilisation = date('Y-m-d');
                $vente->comptabiliser_history = $historique;
                $vente->save();
                
            }else{

                $Export = new ExcelReturn();
                $Export->heureSysteme = GetVenteTraitedDateViaCode(venteCode: $comptabilise->code) ? GetVenteTraitedDateViaCode(venteCode: $comptabilise->code) : "---";

                // $Export->heureSysteme = $comptabilise->heureSysteme;
                // $Export->dateSysteme = $comptabilise->dateSysteme;
                $Export->dateVente = $comptabilise->dateVente;
                $Export->clients = $comptabilise->clients;
                $Export->ifu = $comptabilise->ifu;
                $Export->clientFilleuls = null; // Nouvelle colonne
                $Export->clientFilleulsifu =  null; // Nouvelle colonne
                $Export->dateAchat = $comptabilise->dateAchat;
                $Export->produit = $comptabilise->produit;
                $Export->qte = $comptabilise->qte;
                $Export->pvr = $comptabilise->pvr;
                $Export->prixImpot = $comptabilise->prixTTC;
                $Export->prixImpotHt = $comptabilise->PrixHT;
                $Export->prix1_18 = $comptabilise->PrixBruite;
                $Export->NETHT = $comptabilise->NetHT;
                $Export->TVA = $comptabilise->TVA;
                $Export->AIB = $comptabilise->AIB;
                $Export->TTC = $comptabilise->TTC;
                $Export->FRS = $comptabilise->FRS;
                $comptableCorrect[$key] = $Export;
                $vente = Vente::find($comptabilise->id);
                $historique =[]; 
                if($vente->comptabiliser_history){
                    $historique = json_decode($vente->comptabiliser_history);
                }
                $historique[] = [
                    'user' => Auth::user()->id,
                    'date' => date('Y-m-d H:i'),
                    'type' => 'ReExporter',
                ];
                $historique = json_encode($historique);
                $vente->date_comptabilisation = date('Y-m-d');
                $vente->comptabiliser_history = $historique;
                $vente->save();
            }
        }
        return collect($comptableCorrect);
    }
}
