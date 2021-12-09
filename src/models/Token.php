<?php 
namespace Quizion\Backend\Models;
use Illuminate\Database\Eloquent\Model;

class Token extends Model {
    protected $table = "token";
    protected $guarded = ["id"];
    protected $hidden = ["id","token","created_at","updated_at"];
    
    static public function getName(){
        return "token";
    }
}
