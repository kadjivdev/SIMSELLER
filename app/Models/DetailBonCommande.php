<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBonCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'bon_commande_id', 'produit_id', 'qteCommander', 'pu', 'remise', 'qteValider', 'users'
    ];


    public function boncommande()
    {
        return $this->belongsTo(BonCommande::class, 'bon_commande_id', 'id');
    }


    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id', 'id');
    }


    public function programmations()
    {
        return $this->hasMany(Programmation::class, 'detail_bon_commande_id', 'id');
    }
}
