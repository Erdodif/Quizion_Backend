<?php 
namespace Quizion\Backend;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model {
    protected $table = "answer";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["question_id", "is_right"];
    
    static public function getName(){
        return "answer";
    }
}
