<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];


    public function accusedocuments()
    {
        return $this->hasMany(AccuseDocument::class, 'type_document_id', 'id');
    }
    
}
