<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDetailRecu extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];


    public function detailrecus()
    {
        return $this->hasMany(DetailRecu::class, 'type_detail_recu_id', 'id');
    }
}
