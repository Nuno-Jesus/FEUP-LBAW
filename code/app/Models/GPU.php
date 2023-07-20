<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GPU extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'gpu';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'memory', 'coreclock', 'boostclock ', 'hdmiports ', 'displayports', 'id_product'];

    //Linking GPU to Product table overriding the FK name
    public function products(){
        return $this->belongsTo('App\Models\Product', 'id');
    }
}

?>