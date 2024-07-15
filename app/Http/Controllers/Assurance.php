<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'police', 'dateDebut', 'dateFin', 'compagnie', 'camion_id', 'document'
    ];

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'camion_id', 'id');
    }
}
