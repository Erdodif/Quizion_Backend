<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Data;
use App\Companion\Message;
use Error;
use Exception;
use App\Companion\ResponseCodes;

abstract class Table extends Model
{
    static function getById($id): Data
    {
        try {
            if (!Data::idIsValid($id)) {
               return new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = self::find($id);
                if (!isset($element["id"])) {
                    return new Data(
                        ResponseCodes::ERROR_NOT_FOUND,
                        new Message("Resource not found!")
                    );
                } else {
                    return new Data(
                        ResponseCodes::RESPONSE_OK,
                        $element
                    );
                }
            }
        } catch (Error $e) {
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        }
    }

    static function getByIds($ids): Data
    {
        try {
            $idsAreValid = false;
            try {
                $i = 0;
                while ($i < count($ids) && Data::idIsValid($ids[$i])) {
                    $i++;
                }
                $idsAreValid = $i >= count($ids);
            } catch (Error $e) {
                $idsAreValid = false;
            }
            if (!$idsAreValid) {
                return new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = self::whereIn("id", $ids)->get();
                if (!isset($element[0]["id"])) {
                    return new Data(
                        ResponseCodes::ERROR_NOT_FOUND,
                        new Message(static::getName() . " not found!")
                    );
                } else {
                    return new Data(
                        ResponseCodes::RESPONSE_OK,
                        $element
                    );
                }
            }
        } catch (Error $e) {
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        }
    }

    static function deleteById($id): Data
    {
        $result = self::getById($id);
        try {
            if ($result->getCode() !== ResponseCodes::RESPONSE_OK) {
                return $result;
            }
            $result->getDataRaw()->delete();
            return new Data(
                ResponseCodes::RESPONSE_NO_CONTENT,
                null
            );
        } catch (Error $e) {
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e)
            );
        }
    }
}
