<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero', 'intitule','banque_id',
    ];

    public function banque()
    {
        return $this->belongsTo(Banque::class, 'banque_id', 'id');
    }

    public function detailrecus()
    {
        return $this->hasMany(DetailRecu::class, 'compte_id', 'id');
    }
}
