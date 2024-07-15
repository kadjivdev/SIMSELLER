<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banque extends Model
{
    use HasFactory;

    protected $fillable = [
        'sigle', 'raisonSociale', 'telephone', 'email', 'interlocuteur', 'adresse',
    ];


    public function comptes()
    {
        return $this->hasMany(Compte::class, 'banque_id', 'id');
    }

}
