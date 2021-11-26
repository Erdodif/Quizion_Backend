<?php
namespace Quizion\Backend;
use Illuminate\Database\Eloquent\Model;

class Question extends Model {
    protected $table = "question";
    public $timestamps = false;
    protected $guarded = ["id"];
}
