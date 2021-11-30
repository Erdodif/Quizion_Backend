<?php
namespace Quizion\Backend;
use Illuminate\Database\Eloquent\Model;

class Question extends Model {
    protected $table = "question";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["quiz_id"];

    static public function getName(){
        return "question";
    }
}
