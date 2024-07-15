<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule', 'civilite', 'nom', 'prenom', 'telephone', 'email', 'photo', 'type_avaliseur_id'
    ];


    public function camions()
    {
        return $this->hasMany(Camion::class, 'avaliseur_id', 'id');
    }


    public function typeavaliseur()
    {
        return $this->belongsTo(TypeAvaliseur::class, 'type_avaliseur_id');
    }
}
