<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'purchase';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'orderdate', 'deliverydate', 'orderstatus', 'id_customer', 'id_address', 'id_paymentmethod', 'id_cart'];

    //Linking Purchase to Customer table overriding the FK name
    public function customers(){
        return $this->belongsTo('App\Models\Customer', 'id_customer');
    }

    //Linking Purchase to Address table overriding the FK name
    public function addresses(){
        return $this->belongsTo('App\Models\Address', 'id_address');
    }

    //Linking Purchase to Cart table overriding the FK name
    public function carts(){
        return $this->hasOne('App\Models\Cart', 'id_cart');
    }

    //Linking Purchase to PaymentMethods table overriding the FK name
    public function paymentMethods(){
        return $this->belongsTo('App\Models\PaymentMethod', 'id_PaymentMethod');
    }
}

?>