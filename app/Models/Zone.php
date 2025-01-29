<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'representant_id',
        'departement_id',
    ];

    public function representant()
    {
        return $this->belongsTo(Representant::class, 'representant_id', 'id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'id');
    }

    public function programmations()
    {
        return $this->hasMany(Programmation::class, 'zone_id', 'id');
    }

    public function Commandeclients()
    {
        return $this->hasMany(CommandeClient::class, 'zone_id', 'id');
    }

    public function prix()
    {
        return $this->hasMany(Prix::class);
    }

    public function _Clients(): HasMany
    {
        return $this->hasMany(Client::class, "zone_id");
    }

    public function user($zoneId)
    {
        return User::where("zone_id", $zoneId)->first();
    }
}
