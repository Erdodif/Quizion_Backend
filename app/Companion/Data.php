<?php

namespace App\Companion;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Model;
use \Error;
use Exception;
use App\Companion\ResponseCodes;

class Data
{
    private ?int $code;
    private Message|Model|EloquentCollection|SupportCollection|null $data;
    private array|null $hidden;
    private array|null $visible;

    function __construct(int $code = null, Message|Model|EloquentCollection|SupportCollection|null $data = null)
    {
        if ($code == null) {
            $this->code = ResponseCodes::RESPONSE_OK;
        } else {
            $this->code = $code;
        }
        $this->data = $data;
    }

    function getCode() {
        return $this->code;
    }

    function getDataRaw() {
        return $this->data;
    }

    function setCode(int $code)
    {
        $this->code = $code;
    }

    function setData(Message|Model|EloquentCollection|SupportCollection|null $data)
    {
        $this->data = $data;
    }

    function toJson():string
    {
        $out = "{}";
        if ($this->data !==null) {
            $out = $this->data->toJson();
        }
        return $out;
    }

    function toResponse()
    {
        $content = "";
        if ($this->data !== null) {
            $content = $this->toJson();
        }
        return response($content,$this->getCode())->header("Content-Type", "application/json");
    }

    static function idIsValid($id): bool
    {
        return is_numeric($id) && $id > 0;
    }

    /**
     * @throws Error On invalid Json formatted string
     */
    static function castArray(array|string &$array) {
        try{
            $array = json_decode($array, true, 512, JSON_THROW_ON_ERROR);
        }
        catch(Error $e){}
    }

    /**
     * @throws Error On invalid Json formatted string
     */
    static function inputErrors(array|null $input, array $lookup):array|false
    {
        $array = array();
        if ($input === null) {
            $array = $lookup;
        }
        else{
            foreach ($lookup as $value) {
                if (!isset($input[$value])) {
                    array_push($array,$value);
                }
            }
        }
        return $array;
    }
}
