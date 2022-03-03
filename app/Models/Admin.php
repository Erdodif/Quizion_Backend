<?php

namespace App\Models;

use Error;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = "admin";
    protected $guarded = ["id"];
    public $timestamps = false;

    use HasFactory;

    function user(): User
    {
        return $this->belongsTo(User::class)->first();
    }

    static function isAdmin(int $id): bool
    {
        try {
            Admin::where("user_id", $id)->firstOrFail();
            return true;
        } catch (Error | Exception $e) {
            return false;
        }
    }
}
