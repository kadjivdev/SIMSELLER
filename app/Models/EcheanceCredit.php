<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcheanceCredit extends Model
{
    use HasFactory;
    protected $fillable = ['date','statut','vente_id','user_id'];

    public function vente(){
        return $this->belongsTo(Vente::class);
    }
}
