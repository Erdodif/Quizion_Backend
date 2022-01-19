<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Message;
use App\Companion\Data;
use \Error;
use \Exception;
use App\Companion\ResponseCodes;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\ErrorHandler\Collecting;

class Question extends Table
{
    protected $table = "question";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["quiz_id"];
    static function getName(): string
    {
        return "Question";
    }
    static function getRequiredColumns(): array
    {
        return ["quiz_id", "content", "point"];
    }

    

    function answers(): Collection|null
    {
        $collection = $this->hasMany(Answer::class)->get();
        return Data::collectionOrNull($collection);
    }

    function answer(int $order): Answer|null
    {
        $collection = $this->hasMany(Answer::class)->get();
        if ($collection->count() < $order) {
            return null;
        }
        return $collection[$order - 1];
    }

    function quiz(): Quiz
    {
        return $this->belongsTo(Quiz::class)->first();
    }
}
