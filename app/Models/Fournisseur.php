<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'sigle', 'raisonSociale', 'telephone', 'email', 'adresse', 'categorie_fournisseur_id', 'logo',
    ];


    public function categoriefournisseur()
    {
        return $this->belongsTo(CategorieFournisseur::class, 'categorie_fournisseur_id', 'id');
    }

    public function interlocuteurs()
    {
        return $this->hasMany(Interlocuteur::class, 'fournisseur_id', 'id');
    }

    public function telephones()
    {
        return $this->hasMany(Telephone::class, 'fournisseur_id', 'id');
    }

    
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commercialisers', 'fournisseur_id', 'produit_id');
    }


    public function boncommandes()
    {
        return $this->hasMany(BonCommande::class, 'fournisseur_id', 'id');
    }

}
