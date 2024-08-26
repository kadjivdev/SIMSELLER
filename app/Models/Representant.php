<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representant extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule', 'civilite', 'nom', 'prenom', 'telephone' , 'telephonepro', 'email', 'photo'
    ];


    public function zones()
    {
        return $this->hasMany(Zone::class, 'representant_id', 'id');
    }

    public function users(){
        return $this->hasMany(User::class,'representent_id','id');
    }
}
