<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    protected $fillable = ['id','nom','prenom','telephone','adresse'];

    public function clients()
    {
        return $this->belongsTo(Client::class,'portefeuille');
    }
}
