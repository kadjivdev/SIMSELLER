<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogUser extends Model
{
    use HasFactory;
    protected $fillable = [
        "details","nature_operation","table_name","user_id"
    ];
  
}
