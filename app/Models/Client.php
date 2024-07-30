<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'civilite', 'nom', 'prenom', 'photo', 'sigle', 'raisonSociale', 'logo', 'telephone', 'parent', 'numerocompte', 'email', 'adresse', 'domaine', 'statutCredit', 'sommeil', 'type_client_id', 'agent_id', 'credit', 'filleulFisc', 'departement_id', 'bordereau_receit'
    ];

    public function typeclient()
    {
        return $this->belongsTo(TypeClient::class, 'type_client_id', 'id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'id');
    }

    public function vente()
    {
        return $this->hasMany(Vente::class);
    }

    public function commandeclients()
    {
        return $this->hasMany(CommandeClient::class, 'client_id', 'id');
    }

    public function compteClients()
    {
        return $this->hasMany(CompteClient::class, 'client_id', 'id');
    }

    public function agents()
    {
        return $this->belongsTo(Agent::class, 'portefeuille');
        /* ->withPivot('datedebut','datefin','statut')
        ->withTimestamps(); */
    }

    public function getFilleulFiscAttribute($value)
    {
        return !is_null($value) ? json_decode($value, true) : [];
    }

    public function _detteReglements()
    {
        return $this->hasMany(DetteReglement::class, 'client', 'id');
    }
}
