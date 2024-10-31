<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetteReglement extends Model
{
    use HasFactory;

    protected $fillable = ["reference", "date", "montant", "document", "client", "type_detail_recu", "compte", "operator","reglement_id"];

    function _Client(): BelongsTo
    {
        return $this->belongsTo(Client::class, "client");
    }

    function _TypeDetailRecu(): BelongsTo
    {
        return $this->belongsTo(TypeDetailRecu::class, "type_detail_recu");
    }

    function _Compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class, "compte");
    }

    function _Operator(): BelongsTo
    {
        return $this->belongsTo(User::class, "operator");
    }

    function _Reglement(): BelongsTo
    {
        return $this->belongsTo(Reglement::class, "reglement_id");
    }
}
