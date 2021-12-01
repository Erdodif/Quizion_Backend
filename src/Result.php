<?php
namespace Quizion\Backend;
use Illuminate\Database\Eloquent\Model;

class Result extends Model{
    protected $table = "results";
    public $timestamps = false;
    protected $guarded = ["id"];
    //protected $hidden = [""];
    
    static public function getName(){
        return "results";
    }
}