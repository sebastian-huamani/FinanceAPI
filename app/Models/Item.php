<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title' ,'body', 'amount', 'template_id', 'especial', 'created_at', 'updated_at'];
    protected $casts = ['body' => 'array'];
    protected $table = 'items';

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

    static public function shortDataLending(int $id) {
        Self::select('landings.id', 'items.title')->where('id', $id)->join('landings', 'lendings.item_id', 'items.id')->first()
    }
}