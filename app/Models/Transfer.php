<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'transfer';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'entity', 'reference', 'validfor', 'id_paymentmethod'];

    //Linking Transfer to PaymentMethod table overriding the FK name
    public function paymentMethods(){
        return $this->belongsTo('App\Models\PaymentMethod', 'id');
    }
}

?>