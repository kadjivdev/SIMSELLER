<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieFournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];


    public function fournisseurs()
    {
        return $this->hasMany(Fournisseur::class, 'categorie_fournisseur_id', 'id');
    }
    

}
