<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    use HasFactory;
    protected $fillable = [
        'libelleMvt','dateMvt','montantMvt','compteClient_id','user_id','sens','reglement_id','destroy'
    ];

    public function compteClient(){
        return $this->belongsTo(CompteClient::class,'compteClient_id');
    }
}
