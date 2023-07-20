<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'review';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'text', 'rating', 'yesvotes', 'novotes', 'id_customer', 'id_product'];

    //Linking Review to Customer table overriding the FK name
    public function customers(){
        return $this->belongsTo('App\Models\Customer', 'id_customer');
    }

    //Linking Review to Product table overriding the FK name
    public function products(){
        return $this->belongsTo('App\Models\Product', 'id_product');
    }
}

?>