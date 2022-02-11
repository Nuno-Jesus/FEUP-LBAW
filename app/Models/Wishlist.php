<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model {
  //The relation does not have created_at and updated_at columns
  public $timestamps = false;

  //Relation primary key
  public $primaryKey = 'id_customer';

  //Defines the table's name
  protected $table = 'wishlist';
  
  //Attributes of the relation that can be modified upon creation or update
  protected $fillable = ['id_customer', 'id_product'];

  public function customers(){
    return $this->belongsTo('App\Models\Customer');
  }

  public function products(){
    return $this->belongsTo('App\Models\Product');
  }    
}