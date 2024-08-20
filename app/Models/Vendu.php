<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendu extends Model
{
    use HasFactory;

    protected $fillable = [
        'vente_id', 'programmation_id', 'qteVendu', 'pu', 'remise', 'users'
    ];

    public function vente()
    {
        return $this->belongsTo(Vente::class, 'vente_id', 'id');
    }

    public function programmation()
    {
        return $this->belongsTo(Programmation::class, 'programmation_id', 'id');
    }
}
