<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'date', 'montant', 'statut', 'commande_client_id', 'users','pu','qteTotal','remise',
        'produit_id','type_vente_id','vente_validation','historiques','transport','destination','ask_history',
        'ctl_payeur','date_comptabilisation','taux_aib','taux_tva','prix_impot','prix_achat','marge',
        'date_traitement','user_traitement','date_envoie_commercial','user_envoie_commercial','filleuls','statut_reglement'
    ];

    public function vendus()
    {
        return $this->hasMany(Vendu::class, 'vente_id', 'id');
    }


    public function commandeclient()
    {
        return $this->belongsTo(CommandeClient::class, 'commande_client_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class,'users');
    }

    public function payeur(){
        return $this->belongsTo(Client::class,'ctl_payeur','id');
    }

    public function produit(){
        return $this->belongsTo(Produit::class);
    }

    public function reglements(){
        return $this->hasMany(Reglement::class,'vente_id');
    }

    public function echeances(){
        return $this->hasMany(EcheanceCredit::class);
    }
    public function typeVente(){
        return $this->belongsTo(TypeCommande::class);
    }
    public function getFilleulsAttribute($value){
        return $value ? json_decode($value,true) : [];
    }
    /*
    public function getAsk_historyAttribute($value){
        return $value ? json_decode($value,true) : [];
    } 
    */
}
