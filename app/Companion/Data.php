<?php

namespace App\Companion;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use \Error;

require_once "responseCodes.php";

class Data
{
    private ?int $code;
    private Message|Model|Collection|null $data;
    private array|null $hidden;
    private array|null $visible;

    function __construct(int $code = null, Message|Model|Collection|null $data = null)
    {
        if ($code == null) {
            $this->code = RESPONSE_OK;
        } else {
            $this->code = $code;
        }
        $this->data = $data;
    }

    function getCode(){
        return $this->code;
    }

    function getDataRaw(){
        return $this->data;
    }

    function setCode(int $code)
    {
        $this->code = $code;
    }

    function setData(Message|Model|Collection|null $data)
    {
        $this->data = $data;
    }

    function toJson():string
    {
        $out = "{}";
        if ($this->data !==null){
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
        return response($content,$this->getCode())->header("Content-Type","application/json");
    }

    static function idIsValid($id): bool
    {
        return is_numeric($id) && $id > 0;
    }

    /**
     * @throws Error On invalid Json formatted string
     */
    static function castArray(array|string $array):array{
        
        if (!($array instanceof ('array'))) {
            $array = json_decode($array, true);
            if ($array === null) {
                throw new Error("Json format invalid!");
            }
        }
        return $array;
    }

    /**
     * @throws Error On invalid Json formatted string
     */
    static function inputErrors(array|string|null $input, array $lookup):array|false
    {
        $array = array();
        if ($input == null){
            $array = $lookup;
        }
        else{
            $input = Data::castArray($input);
            foreach ($lookup as $value){
                if (!isset($input[$value])){
                    array_push($array,$value);
                }
            }
        }
        return $array;
    }
}
