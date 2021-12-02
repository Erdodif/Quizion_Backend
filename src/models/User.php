<?php
namespace Quizion\Backend\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model{
    protected $table = "user";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["email","password"];
    
    static public function getName(){
        return "user";
    }
}