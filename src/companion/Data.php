<?php

namespace Quizion\Backend\Companion;

use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    function withResponse(Response $response)
    {
        if ($this->data !== null) {
            $response->getBody()->write($this->toJson());
        }
        return $response->withHeader("Content-Type", "application/json")->withStatus($this->code);
    }

    static function idIsValid($id): bool
    {
        return is_numeric($id) && $id > 0;
    }
}
