<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    //Defines the table's name
    protected $table = 'User';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'username', 'email', 'password', 'phone', 'isbanned', 'isadmin', 'profilepic'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password' 
    ];

    public function admins(){
        return $this->hasMany('App\Models\Admin', 'id');
    }

    public function customers(){
        return $this->hasMany('App\Models\Customer', 'id');
    }
}


?>