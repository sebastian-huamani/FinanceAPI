<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title' ,'body', 'amount', 'template_id', 'landing_id', 'created_at', 'updated_at'];
    protected $casts = ['body' => 'array'];

    //Many To Many (Polymorphic)
    public function cards(){
        return $this->morphToMany('App\Models\Card', 'transactionable');
    }

    public function template(){
        return $this->belongsTo(Template::class);
    }

    public function landing() {
        return $this->hasOne(Landing::class);
    }

    public function scopeByState($query, int $state_id) {
        $query->join('landings', 'items.id', 'landings.item_id')->where('landings.state_id', $state_id)->get();
    }

    public function scopeFilterLending($query, $state , $month, $year){
            return $query->whereNot('landing_id', null)
            ->whereYear('items.created_at', $year)
            ->whereMonth('items.created_at', $month)
            ->leftjoin('landings', 'items.landing_id', 'landings.id')
            ->where('landings.state_id', $state )
            ->orderby('items.created_at', 'desc');
    }

    
}