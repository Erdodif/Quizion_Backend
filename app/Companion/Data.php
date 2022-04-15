<?php

namespace App\Companion;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Model;
use \Error;
use App\Companion\ResponseCodes;

class Data
{
    private ?int $code;
    private Message|Model|EloquentCollection|SupportCollection|null $data;
    private array|null $hidden;
    private array|null $visible;

    function __construct(int $code = null, Message|Model|EloquentCollection|SupportCollection|array|null $data = null, Message ...$messages)
    {
        if ($code == null) {
            $this->code = ResponseCodes::RESPONSE_OK;
        } else {
            $this->code = $code;
        }
        $this->data = $data;
        if($messages !== null && is_a($data, Message::class)){
            foreach($messages as $message){
                $this->data->addMessage($message);
            }
        }
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

    /**
     * Creates a JSON string out of the object's data
     * 
     * @return string The JSON-formatted data
     */
    function toJson():string
    {
        $out = "{}";
        if ($this->data !== null) {
            $out = $this->data->toJson();
        }
        return $out;
    }

    /**
     * Creates a response with the object's response code, and data. 
     * 
     * The data is following the application/json mime stantard
    */
    function toResponse()
    {
        $content = "";
        if ($this->data !== null) {
            $content = $this->toJson();
        }
        return response($content,$this->getCode())->header("Content-Type", "application/json");
    }

//Static methods
    /**
     * Basic numeric check on id parameters
     * @param mixed $id The suspicious id
     * @return bool True, if the id is a positive number 
     */
    static function idIsValid($id): bool
    {
        return is_numeric($id) && $id > 0;
    }

    /**
     * Returns the given collection or null, if empty
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|null
     */
    static function collectionOrNull(EloquentCollection|SupportCollection $collection):EloquentCollection|SupportCollection|null
    {
        if ($collection->isNotEmpty()) {
            return $collection;
        } else {
            return null;
        }
    }

    /**
     * Tries to JSON-decode the given string
     */
    static function castArray(array|string &$array) {
        try{
            $array = json_decode($array, true, 512, JSON_THROW_ON_ERROR);
        }
        catch(Error $e){}
    }

    /**
     * Returns those keys from the second array, what the first does not have, 
     * or false, if the first array has all the keys the second array has.
     * @param array|null $input The first array 
     * @param array $lookup The second, lookup array 
     * @return array|false The missing keys, or false, if there are no missing keys
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
