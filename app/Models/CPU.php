<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CPU extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'cpu';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'basefreq', 'turbofreq', 'socket', 'threads', 'cores', 'id_product'];

    //Linking CPU to Product table overriding the FK name
    public function products(){
        return $this->belongsTo('App\Models\Product', 'id');
    }
}

?>