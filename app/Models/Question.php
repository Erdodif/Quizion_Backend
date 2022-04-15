<?php

namespace App\Models;

use App\Companion\Data;
use Illuminate\Database\Eloquent\Collection;

class Question extends Table
{
    protected $table = "question";
    public $timestamps = false;
    protected $guarded = ["id"];
    /*protected $hidden = ["quiz_id"];*/
    
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
