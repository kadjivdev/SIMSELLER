<?php

namespace App\Models;

use DateConverter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'dateBon', 'montant', 'statut', 'type_commande_id', 'fournisseur_id', 'users','date_traitement_bc'
    ];

    public static $rules = [
        'code' => ['required', 'string', 'max:255', 'unique:bon_commandes,code'],
        'dateBon' => ['required', 'date'],
        'type_commande_id' => ['required'],
        'fournisseur_id' => ['required'],
    ];

    public function typecommande()
    {
        return $this->belongsTo(TypeCommande::class, 'type_commande_id', 'id');
    }


    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id', 'id');
    }


    public function detailboncommandes()
    {
        return $this->hasMany(DetailBonCommande::class, 'bon_commande_id', 'id');
    }


    public function accusedocuments()
    {
        return $this->hasMany(AccuseDocument::class, 'bon_commande_id', 'id');
    }


    public function recus()
    {
        return $this->hasMany(Recu::class, 'bon_commande_id', 'id');
    }

}
