<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Other extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'other';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'id_product'];

    //Linking Other to Product table overriding the FK name
    public function products(){
        return $this->belongsTo('App\Models\Product', 'id');
    }
}

?>