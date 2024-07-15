<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recu extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero','libelle', 'date','reference','tonnage', 'montant','document', 'bon_commande_id'
    ];


    public function boncommande()
    {
        return $this->belongsTo(BonCommande::class, 'bon_commande_id', 'id');
    }


    public function detailrecus()
    {
        return $this->hasMany(DetailRecu::class, 'recu_id', 'id');
    }
}
