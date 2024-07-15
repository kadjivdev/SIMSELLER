<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteClient extends Model
{
    use HasFactory;
    protected $fillable = [
        'solde',
        'client_id',
        'user_id'
    ];

    public function client(){
        return $this->belongsTo(Client::class,"client_id");
    }
    public function mouvements(){
        return $this->hasMany(Mouvement::class);
    }
}
