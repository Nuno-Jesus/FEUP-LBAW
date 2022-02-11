<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model {
  //The relation does not have created_at and updated_at columns
  public $timestamps = false;

  //Relation primary key
  public $primaryKey = 'id_cart';

  //Defines the table's name
  protected $table = 'cartproduct';
  
  //Attributes of the relation that can be modified upon creation or update
  protected $fillable = ['id_cart', 'id_product', 'quantity'];

  public function carts(){
    return $this->belongsTo('App\Models\Cart');
  }

  public function products(){
    return $this->belongsTo('App\Models\Product');
  }    
}