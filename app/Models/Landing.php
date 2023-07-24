<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Landing extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'payment_date_lending', 'state_id', 'debtor', 'is_lending', 'is_fee', 'history_quota' ,'card_id' , 'created_at', 'updated_at'];
    protected $casts = ["history_quota" => "array"];

    public function cards()
    {
        return $this->morphToMany('App\Models\Card', 'transactionable');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function Order($items): array{
        date_default_timezone_set('America/Lima');

        $lending = [];
        foreach ($items as $item) {

            $type_lending = [];
            if($item['is_lending'] != 0){
                array_push($type_lending, ['title' => 'Prestamo', 'colorSelected' => "bg-green-200", 'colorSelectedText' => "text-green-900"]);
            }
            if($item['is_fee'] != 0){
                array_push($type_lending, ['title' => 'Cuotas', 'colorSelected' => "bg-blue-300", 'colorSelectedText' => "text-blue-900"]);
            }

            if($item['payment_date_lending'] == null){
                $data = false;
            }else{
                $date_payment  = Carbon::create($item['payment_date_lending'])->format('Y-m-d');
                $dateNow = date('Y-m-d');
                $data = $dateNow > $date_payment;
            }

            $itemOrder = [
                'id' => $item['id'],
                'title' => $item['title'],
                'amount' => $item['amount'],
                'created_at' => $item['created_at'],
                'debtor' => $item['debtor'],
                'type_lending' => $type_lending,
                'state' => $item['state_id'],
                'bank' => $item['card_name'],
                'lending_id' => $item['landing_id'],
                'finish_time' => $data
            ];

            array_push($lending, $itemOrder);
        }

        return $lending;
    }
    
    public static function getLendingsByState(array $state){
        
        return  Landing::join('cards', 'landings.card_id', 'cards.id')
        ->join('items', 'items.landing_id', 'landings.id')
        ->where('cards.user_id', Auth::user()->id)
        ->whereIn('landings.state_id', $state )
        ->select('landings.*', 'items.*', 'cards.name as card_name');
    }  

    public static function getSumActives(){
        return Self::getLendingsByState([1])->sum('landings.amount');
    }  

}
