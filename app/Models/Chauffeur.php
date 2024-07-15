<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chauffeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom','prenom', 'dateNaissance', 'telephone','numero','permis', 'photo', 'statut',
    ];


    public function programmations()
    {
        return $this->hasMany(Programmation::class, 'chauffeur_id', 'id');
    }

}
