<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisiteTechnique extends Model
{
    use HasFactory;

    protected $fillable = [
        'dateDebut', 'dateFin', 'libelle', 'camion_id', 'document'
    ];

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'camion_id', 'id');
    }
}
