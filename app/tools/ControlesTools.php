<?php

namespace App\tools;

use App\Mail\SuspectMail;
use App\Models\LogUser;
use Illuminate\Support\Facades\Mail;

class ControlesTools
{
    public static function generateLog($info,$table,$nature_operation){
        $details = json_encode($info);
        LogUser::create(
            [
                'details' => $details,
                'table_name'=>$table,
                'user_id'=>auth()->user()->id,
                'nature_operation'=>$nature_operation
                
            ]
        );
        $textMail = 'Nous venons constater une opération suspecte. Merci de vous connecter pour les détails.';
        Mail::send(new SuspectMail(['email'=>'arnaudex2013@gmail.com'],'Opération suspecte sur la table '.$table,$textMail));
    }
}