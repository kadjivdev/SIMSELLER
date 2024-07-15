<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRecu extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','reference', 'date', 'montant','document', 'recu_id', 'compte_id', 'type_detail_recu_id'
    ];


    public function recu()
    {
        return $this->belongsTo(Recu::class, 'recu_id', 'id');
    }


    public function compte()
    {
        return $this->belongsTo(Compte::class, 'compte_id', 'id');
    }


    public function typedetailrecu()
    {
        return $this->belongsTo(TypeDetailRecu::class, 'type_detail_recu_id', 'id');
    }
}
