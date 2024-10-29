<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogUser extends Model
{
    use HasFactory;
    protected $fillable = [
        "details","nature_operation","table_name","user_id"
    ];
  
    function actor() : BelongsTo {
        return $this->belongsTo(User::class,"user_id");
    }
}
