<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programmation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','imprimer', 'transfert', 'dateprogrammer', 'qteprogrammer','datelivrer', 'qtelivrer', 'bl','bl_gest', 'document', 'statut',
         'detail_bon_commande_id', 'zone_id', 'avaliseur_id','camion_id', 'chauffeur_id','dateSortie','cloture', 'historiques',"observation"
    ];

    public function detailboncommande()
    {
        return $this->belongsTo(DetailBonCommande::class, 'detail_bon_commande_id', 'id');
    }

    public function vendus()
    {
        return $this->hasMany(Vendu::class, 'programmation_id', 'id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }

    public function avaliseur()
    {
        return $this->belongsTo(Avaliseur::class, 'avaliseur_id', 'id');
    }

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'camion_id', 'id');
    }


    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class, 'chauffeur_id', 'id');
    }
    public function getHistoriquesAttribute($value){
        return $value ? json_decode($value,true) : [];
    }
}
