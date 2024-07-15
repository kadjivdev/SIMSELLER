<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];

    public function boncommandes()
    {
        return $this->hasMany(BonCommande::class, 'type_commande_id', 'id');
    }


    public function commandeclients()
    {
        return $this->hasMany(CommandeClient::class, 'type_commande_id', 'id');
    }
}
