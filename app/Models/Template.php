<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'state','user_id'];
    protected $casts = ['body' => 'array'];

}
