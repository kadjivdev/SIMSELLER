<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];


    public function zones()
    {
        return $this->hasMany(Zone::class, 'departement_id', 'id');
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
