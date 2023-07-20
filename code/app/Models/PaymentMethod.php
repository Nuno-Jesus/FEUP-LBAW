<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'paymentmethod';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'type'];

    //Linking PaymentMethod to Paypal table overriding FK name
    public function paypals(){
        return $this->hasMany('App\Model\Paypal', 'id');
    }

    //Linking PaymentMethod to Card table overriding FK name
    public function cards(){
        return $this->hasMany('App\Model\Card', 'id');
    }

    //Linking PaymentMethod to Transfer table overriding FK name
    public function tranfers(){
        return $this->hasMany('App\Model\Transfer', 'id');
    }
}

?>