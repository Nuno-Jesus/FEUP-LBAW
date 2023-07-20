<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paypal extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'paypal';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'email', 'id_paymentmethod'];

    //Linking Paypal to PaymentMethod table overriding the FK name
    public function paymentMethods(){
        return $this->belongsTo('App\Models\PaymentMethod', 'id');
    }
}

?>