<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Data;
use App\Companion\Message;
use Error;
use Exception;

abstract class Table extends Model
{
    static abstract function getName(): string;
    static abstract function getRequiredColumns(): array;

    static function addNew(array|string|null $input): Data
    {
        try {
            if ($input === null) {
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("No data provided!")
                );
            } else {
                Data::castArray($input);
                $invalids = Data::inputErrors($input, static::getRequiredColumns());
                if (!$invalids) {
                    $answer = self::create($input);
                    $answer->save();
                    $data = new Data(
                        RESPONSE_CREATED,
                        $answer
                    );
                } else {
                    $out = "";
                    foreach ($invalids as $invalid) {
                        $out .= $invalid . ", ";
                    }
                    $out = substr($out, 0, -2);
                    $data = new Data(
                        ERROR_BAD_REQUEST,
                        new Message("Missing " . $out)
                    );
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_BAD_REQUEST,
                new Message($e)
            );
        } catch (Exception $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message($e)
            );
        } finally {
            return $data;
        }
    }

    static function getById($id): Data
    {
        try {
            if (!Data::idIsValid($id)) {
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = self::find($id);
                if (!isset($element["id"])) {
                    $data = new Data(
                        ERROR_NOT_FOUND,
                        new Message(static::getName() . " not found!")
                    );
                } else {
                    $data = new Data(
                        RESPONSE_OK,
                        $element
                    );
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
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
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = self::whereIn("id", $ids)->get();
                if (!isset($element[0]["id"])) {
                    $data = new Data(
                        ERROR_NOT_FOUND,
                        new Message(static::getName() . " not found!")
                    );
                } else {
                    $data = new Data(
                        RESPONSE_OK,
                        $element
                    );
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
        }
    }

    static function getAll(): Data
    {
        try {
            $result = self::all();
            if (isset($result[0]["id"])) {
                $data = new Data(
                    RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("There is no " . strtolower(static::getName()) . "!")
                );
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
        }
    }

    static function alterById($id, array|string $input): Data
    {
        try {
            Data::castArray($input);
            $result = self::getById($id);
            if ($result->getCode() == RESPONSE_OK) {
                try {
                    $result->getDataRaw()->fill($input);
                    $result->getDataRaw()->save();
                } catch (Error $e) {
                    $result->setCode(ERROR_INTERNAL);
                    $result->setData(new Message("An internal error occured: " . $e));
                }
            }
        } catch (Error $e) {
            $result = new Data(
                ERROR_BAD_REQUEST,
                new Message("The given Data is missing or invalid!")
            );
        }
        return $result;
    }

    static function deleteById($id): Data
    {
        $result = self::getById($id);
        try {
            if ($result->getCode() == RESPONSE_OK) {
                $result->getDataRaw()->delete();
                $result->setCode(RESPONSE_NO_CONTENT);
            }
        } catch (Error $e) {
            $result->setCode(ERROR_INTERNAL);
            $result->setData(new Message("An internal error occured! " . $e));
        }
        return $result;
    }
}
