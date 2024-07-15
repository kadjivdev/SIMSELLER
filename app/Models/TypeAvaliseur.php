<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAvaliseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];


    public function avaliseurs()
    {
        return $this->hasMany(Avaliseur::class, 'type_avaliseur_id', 'id');
    }
}
