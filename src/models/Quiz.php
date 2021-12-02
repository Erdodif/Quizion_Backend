<?php
namespace Quizion\Backend\Models;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model {
    protected $table = "quiz";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["active"]; //Nem kell kiadni, mint később a jelszókat
    
    static public function getName(){
        return "quiz";
    }
}
