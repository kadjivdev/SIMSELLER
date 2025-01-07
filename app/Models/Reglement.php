<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reglement extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'reference',
        'date',
        'montant',
        'document',
        'vente_id',
        'compte_id',
        'type_detail_recu_id',
        'user_id',
        'observation_validation',
        'user_validateur_id',
        'statut',
        'client_id',
        "for_dette",
        "old_solde",
        "clt",
        "debloc_dette"
    ];

    public function vente()
    {
        return $this->belongsTo(Vente::class, 'vente_id', 'id');
    }

    public function compte()
    {
        return $this->belongsTo(Compte::class, 'compte_id', 'id');
    }

    public function typeReglement()
    {
        return $this->belongsTo(TypeDetailRecu::class, 'type_detail_recu_id', 'id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function _mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class, "reglement_id");
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, "client_id");
    }

    public function _clt(): BelongsTo
    {
        return $this->belongsTo(Client::class, "clt");
    }

    function _DetteReglement(): HasOne
    {
        return $this->hasOne(DetteReglement::class, "reglement_id");
    }
}
