<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];


    public function camions()
    {
        return $this->hasMany(Camion::class, 'marque_id', 'id');
    }
}
