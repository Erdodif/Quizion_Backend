<?php
namespace Quizion\Backend;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model{
    protected $table = "quiz";
    public $timestamps = false;
    protected $guarded = ["id"];
}