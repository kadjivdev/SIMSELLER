<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TypeClient extends Model
{
    use HasFactory;
    protected $fillable = [
        'libelle',
        'type_client_id'
    ];


    public function clients()
    {
        return $this->hasMany(Client::class, 'client_id', 'id');
    }
    public function categorieTypes(){
        return $this->hasMany(TypeClient::class);
    }
    public function parent(){
        return $this->belongsTo(TypeClient::class,'type_client_id');
    }

}
