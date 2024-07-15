<?php
namespace App\tools;
use App\Models\Assurance;
use App\Models\Camion;
use App\Models\VisiteTechnique;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\u;

class DateConverter
{

    public static function convertDate($date=null,$format=102){
        if(!$date){
            $date = Carbon::now();
        }
        return DB::raw("CONVERT(DATETIME, '$date', $format)");
    }
    public static function verificateurDateDebutInclut($camion_id,$date,$libelle,$option=0,$update=null){

        if($option == 0){
            if(!$update){
                $req = DB::select("
                SELECT COUNT(*) AS nbre
                FROM assurances
                WHERE ?  BETWEEN dateDebut AND DateFin
                AND camion_id = ?
            ",[$date,$camion_id]);
                return $req[0]->nbre;
            }
            else{
                $assurance = Assurance::find($update);
                if($date == $assurance->dateDebut){
                    return 0;
                }
                else{
                    $req = DB::select("
                SELECT COUNT(*) AS nbre
                FROM assurances
                WHERE ?  BETWEEN dateDebut AND DateFin
                AND camion_id = ?
                AND id <> ?
            ",[$date,$camion_id,$assurance->id]);
                    return $req[0]->nbre;
                }
            }
        }
        else{
            if(!$update){
                $req = DB::select("
                    SELECT COUNT(*) AS nbre
                    FROM visite_techniques
                    WHERE ?  BETWEEN dateDebut AND DateFin
                    AND camion_id = ?
                    AND libelle = ?
                ",[$date,$camion_id,$libelle]);

                return $req[0]->nbre;
            }
            else{
                $vt = VisiteTechnique::find($update);
                if($date == $vt->dateDebut){
                    return 0;
                }
                else{
                    $req = DB::select("
                    SELECT COUNT(*) AS nbre
                    FROM visite_techniques
                    WHERE ?  BETWEEN dateDebut AND DateFin
                    AND camion_id = ?
                    AND id <> ?
                ",[$date,$camion_id,$vt->id]);
                    return $req[0]->nbre;
                }
            }

        }

    }
    public static function verificateurDateFinInclut($camion_id,$dateDebut,$dateFin,$libelle, $option=0,$update=null){
        if($option == 0){
            if(!$update){
                $req = DB::select("
                    SELECT COUNT(*) AS nbre
                    FROM assurances
                    WHERE (?  BETWEEN dateDebut AND DateFin
                    OR dateFin BETWEEN ? AND ?)
                    AND camion_id = ?
                ",[$dateFin,$dateDebut,$dateFin,$camion_id]);
                return $req[0]->nbre;
            }
            else{
                $assurance = Assurance::find($update);
                if($dateFin == $assurance->dateFin){
                    return 0;
                }
                else{
                    $req = DB::select("
                    SELECT COUNT(*) AS nbre
                    FROM assurances
                    WHERE (?  BETWEEN dateDebut AND DateFin
                    OR dateFin BETWEEN ? AND ?)
                    AND camion_id = ?
                    AND id <> ?
                ",[$dateFin,$dateDebut,$dateFin,$camion_id,$assurance->id]);
                    return $req[0]->nbre;
                }
            }

        }
        else{
            if(!$update){
                $req = DB::select("
                SELECT COUNT(*) AS nbre
                FROM visite_techniques
                WHERE (?  BETWEEN dateDebut AND DateFin
                OR dateFin BETWEEN ? AND ?)
                AND camion_id = ?
                AND libelle = ?
            ",[$dateFin,$dateDebut,$dateFin,$camion_id,$libelle]);
                return $req[0]->nbre;
            }
            else{
                $vt = VisiteTechnique::find($update);
                if($dateFin == $vt->dateFin){
                    return 0;
                }
                else{
                    $req = DB::select("
                SELECT COUNT(*) AS nbre
                FROM visite_techniques
                WHERE (?  BETWEEN dateDebut AND DateFin
                OR dateFin BETWEEN ? AND ?)
                AND camion_id = ?
                AND id <> ?
            ",[$dateFin,$dateDebut,$dateFin,$camion_id,$vt->id]);
                    return $req[0]->nbre;
                }
            }


        }

    }

    public static function checkDate($debut,$fin,$camion,$libelle,$update=null): bool
    {

        $checkdebut = self::verificateurDateDebutInclut($camion,$debut,$libelle,1,$update);

        $checkfin = self::verificateurDateFinInclut($camion,$debut,$fin, $libelle,1,$update);

        if($checkdebut == 0 && $checkfin == 0)
            $rst = true;
        else
            $rst = false;

        return $rst;
    }
    public static function checkDateAss($debut,$fin,$camion,$update=null){

        $checkdebut = self::verificateurDateDebutInclut($camion,$debut,'',0,$update);

        $checkfin = self::verificateurDateFinInclut($camion,$debut,$fin,'',0,$update);
        if($checkdebut == 0 && $checkfin == 0)
            $rst = true;
        else
            $rst = false;

        return $rst;
    }
}
