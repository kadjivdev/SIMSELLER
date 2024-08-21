<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeletedVente extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function _TypeVente():BelongsTo
    {
        return $this->belongsTo(TypeCommande::class);
    }

    public function commandeclient():BelongsTo
    {
        return $this->belongsTo(CommandeClient::class, 'commande_client_id', 'id');
    }
}
