<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'dateBon', 'montant', 'statut', 'type_commande_id', 'client_id', 'zone_id', 'users','byvente'
    ];

    public function typecommande()
    {
        return $this->belongsTo(TypeCommande::class, 'type_commande_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function vente()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }

    public function commanders()
    {
        return $this->hasMany(Commander::class, 'commande_client_id', 'id');
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class, 'commande_client_id', 'id');
    }

    function user(){
        return $this->belongsTo(User::class,'users');
    }
}
