<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigCompta extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'intitule','user_id','metadonnee','valeur',
    ];
}
