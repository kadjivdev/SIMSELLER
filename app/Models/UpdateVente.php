<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpdateVente extends Model
{
    use HasFactory;

    protected $table = "update_ventes";

    protected $fillable = [
        "vente", "demandeur", "raison", "prouve_file", "valide"
    ];

    function _Vente(): BelongsTo
    {
        return $this->belongsTo(Vente::class, "vente");
    }

    function _Demandeur(): BelongsTo
    {
        return $this->belongsTo(User::class, "demandeur");
    }
}
