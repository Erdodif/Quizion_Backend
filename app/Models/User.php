<?php

namespace App\Models;

use App\Companion\Data;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    protected $table = "users";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["email", "password", "remember_token"];
    protected $casts = ["email_verified_at" => "datetime"];

    function results(): Collection|null
    {
        $collection = $this->hasMany(Result::class)->get();
        return Data::collectionOrNull($collection);
    }

    function tokens()
    {
        $collection = $this->hasMany(Token::class)->get();
        return Data::collectionOrNull($collection);
    }
}
