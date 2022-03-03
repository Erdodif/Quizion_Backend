<?php

namespace App\Models;

use App\Companion\Data;
use Illuminate\Database\Eloquent\Collection;

class Quiz extends Table
{
    protected $table = "quiz";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["active"];

    function questions(): Collection|null
    {
        $collection = $this->hasMany(Question::class)->get();
        return Data::collectionOrNull($collection);
    }

    function question(int $order): Question|null
    {
        $collection = $this->hasMany(Question::class)->get();
        if ($collection->count() < $order) {
            return null;
        }
        return $collection[$order - 1];
    }

    function answers(int $order): Collection|null
    {
        $question = $this->question($order);
        if ($question === null) {
            return null;
        }
        $collection = $question->answers();
        return Data::collectionOrNull($collection);
    }

    function results(): Collection|null
    {
        return $this->hasMany(Result::class)->get();
    }

    function UserIdQuizIdValid(int $id): bool
    {
        return $this->user_id === $id;
    }
}
