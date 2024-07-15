<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle', 'description', 'photo', 'type_produit_id','prix_fourniture'
    ];

    public function typeproduit()
    {
        return $this->belongsTo(TypeProduit::class, 'type_produit_id');
    }


    public function fournisseurs()
    {
        return $this->belongsToMany(Fournisseur::class, 'commercialisers', 'produit_id', 'fournisseur_id');
    }


    public function boncommandes()
    {
        return $this->belongsToMany(BonCommande::class, 'detail_bon_commandes', 'produit_id', 'bon_commande_id')->withPivot('qteCommander', 'pu', 'remise', 'qteValider');
    }


    public function detailboncommandes()
    {
        return $this->hasMany(Produit::class, 'produit_id', 'id');
    }

}
