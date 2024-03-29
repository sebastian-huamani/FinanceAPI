<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // One To Many
    public function cards(){
        return $this->hasMany(Card::class);
    }

    // One To Many
    public function sessionDivices()
    {
        return $this->hasMany(sessionDivice::class);
    }

    // One To Many
    public function templates(){
        return $this->hasMany(Template::class);
    }

    public function landings(){
        return $this->hasMany(Landing::class);
    }

    public function date_card(){
        return $this->hasOne(DateCard::class);
    }

    public function data_info_user(){
        return $this->hasOne(DataInfoUser::class);
    }
    
}
