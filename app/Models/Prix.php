<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prix extends Model
{
    use HasFactory;
    protected $fillable = [

        'id', 'datePriseEffet',
        'prix', 'dateFin', 'status',
        'zone_id', 'user_id'
    ];


    public function zone()
    {
        return $this->belongsTo(Zone::class,'zone_id','id');
    }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
