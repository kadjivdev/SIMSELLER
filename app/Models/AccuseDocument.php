<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccuseDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','libelle', 'date', 'montant' , 'reference','document','observation', 'type_document_id', 'bon_commande_id'
    ];


    public function boncommande()
    {
        return $this->belongsTo(BonCommande::class, 'bon_commande_id', 'id');
    }


    public function typedocument()
    {
        return $this->belongsTo(TypeDocument::class, 'type_document_id', 'id');
    }
}
