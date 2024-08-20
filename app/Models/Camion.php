<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Camion extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque_id', 'immatriculationTracteur', 'immatriculationRemorque', 'nombreIssieu', 'tonnage', 'statut', 'photo', 'chauffeur_id', 'avaliseur_id'
    ];

    public static $Rules = [
        'marque' => ['required', 'string', 'max:255'],
        'immatriculationTracteur' => ['required', 'unique:camions,immatriculationTracteur'],
        'immatriculationRemorque' => ['required', 'unique:camions,immatriculationRemorque'],
        'nombreIssieu' => ['required'],
        'tonnage' => ['required'],
        'statut' => ['required'],
    ];


    /*public static $updateRules = [
        'marque' => ['required', 'string', 'max:255'],
        'immatriculationTracteur' => ['required', 'unique:camions,immatriculationTracteur', Rule::unique('camions')->ignore($camions->id)],
        'immatriculationRemorque' => ['required', 'unique:camions,immatriculationRemorque', Rule::unique('camions')->ignore($camions->id)],
        'nombreIssieu' => ['required'],
        'statut' => ['required'],
    ];*/

    public function assurances()
    {
        return $this->hasMany(Assurance::class, 'camion_id', 'id');
    }


    public function visitetechniques()
    {
        return $this->hasMany(VisiteTechnique::class, 'camion_id', 'id');
    }

    public function programmations()
    {
        return $this->hasMany(Programmation::class, 'camion_id', 'id');
    }

    public function avaliseur()
    {
        return $this->belongsTo(Avaliseur::class, 'avaliseur_id', 'id');
    }

    public function chauffeur(){
        return $this->belongsTo(Chauffeur::class);
    }
    public function marque()
    {
        return $this->belongsTo(Marque::class, 'marque_id', 'id');
    }
}
