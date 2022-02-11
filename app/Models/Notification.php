<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    
    //The relation does not have created_at and updated_at columns
    public $timestamps = false;

    //Defines the table's name
    protected $table = 'notification';
    
    //Attributes of the relation that can be modified upon creation or update
    protected $fillable = ['id', 'content', 'id_customer'];

    //Linking Notification to Customer table overriding FK name
    public function customers(){
        return $this->belongsTo('App\Model\Customer', 'id_customer');
    }
}

?>