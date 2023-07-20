<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'customer';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'id_user'];

    //Pointing Customer to Account table overriding FK name
    public function users(){
        return $this->belongsTo('App\Models\User', 'id');
    }

    //Pointing Customer to Notification table overriding FK name
    public function notifications(){
        return $this->hasMany('App\Models\Notification', 'id_customer');
    }

    //Pointing Customer to Purchase table overriding FK name
    public function purchases(){
        return $this->hasMany('App\Models\Purchase', 'id_customer');
    }

    //Pointing Customer to CustomerAddress table overriding its name and FKs
    public function addresses(){
        return $this->belongsToMany('App\Models\Address', 'customeraddress', 'id_customer', 'id_address');
    }

    //Pointing Customer to Cart table overriding FK name
    public function carts(){
        return $this->hasMany('App\Models\Cart', 'id_customer');
    }

    //Pointing Customer to Wishlist table overriding its name and FKs
    public function products(){
        return $this->belongsToMany('App\Models\Product', 'wishlist', 'id_customer', 'id_product');
    }

    //Pointing Customer to Review table overriding FK name
    public function reviews(){
        return $this->hasMany('App\Models\Review', 'id_customer');
    }


}

?>