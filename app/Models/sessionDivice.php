<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sessionDivice extends Model
{
    use HasFactory;

    protected $fillable = ['client_ip', 'browser', 'user_id', 'created_at', 'updated_at' ];

}
