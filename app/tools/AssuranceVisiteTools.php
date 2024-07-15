<?php

namespace App\tools;

class AssuranceVisiteTools
{

    public static function getControleStat($actifs,$encours){

        if(count($actifs) > 0){
            return 1;
        }
        elseif (count($encours) > 0){
            return  2;
        }
        else{
            return 0;
        }
    }
}
