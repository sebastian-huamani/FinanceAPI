<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title' ,'body', 'amount', 'template_id', 'created_at', 'updated_at'];
    protected $casts = ['body' => 'array'];

    //Many To Many (Polymorphic)
    public function cards(){
        return $this->morphToMany('App\Models\Card', 'transactionable');
    }

    public function template(){
        return $this->belongsTo(Template::class);
    }

    public function landings() {
        return $this->hasMany(Landing::class);
    }
}