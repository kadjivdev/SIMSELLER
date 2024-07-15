<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelReturn extends Model
{
    use HasFactory;
    protected $fillable = [
        'heureSysteme',
        'dateSysteme',
        'dateVente',
        'clients',
        'ifu',
        'clientFilleuls', // Nouvelle colonne
        'clientFilleulsifu', // Nouvelle colonne
        'dateAchat',
        'produit',
        'qte',
        'pvr',
        'prix TTC',
        'prix Ht',
        'prix1_18',
        'NETHT',
        'TVA',
        'AIB',
        'TTC',
        'FRS'
    ];
}
