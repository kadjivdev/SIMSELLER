<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Porteuille extends Model
{
    use HasFactory;
    protected $table ='portefeuilles';
    protected $fillable = [

       'id', 'datedebut','datefin','statut','client_id','agent_id'
       
    ];
}
