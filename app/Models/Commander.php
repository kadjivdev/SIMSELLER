<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commander extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_client_id', 'produit_id', 'qteCommander', 'pu', 'remise', 'qteValider', 'users'
    ];


    public function commandeclient()
    {
        return $this->belongsTo(CommandeClient::class, 'commande_client_id', 'id');
    }


    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id', 'id');
    }
}
