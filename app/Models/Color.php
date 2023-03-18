<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

        protected $fillable = ['color', 'color_panel_top', 'color_panel_bottom', 'color_type', 'color_button'];

    
}
