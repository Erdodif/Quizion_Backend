<?php
header("Content-type: application/json");
require_once "Adatbazis.php";
require_once "tables/Tables.php";
$db = new Adatbazis();
$response = array();
$response["error"] = false;
$method = $_SERVER["REQUEST_METHOD"];
switch ($method) {
    case "OPTIONS":
        try {
            $tabla = $_GET["table"] ?? null;
            if (!empty($tabla)) {
                if ($tabla === "*") {
                    $response["tables"] = $db->info($tabla);
                } else {
                    $response["table"] = $tabla;
                    $response["columns"] = $db->info($tabla);
                }
            } else {
                $response["error"] = true;
                $response["message"] = "Nincs kiválasztott tábla!";
            }
        } catch (Error $e) {
            $response["error"] = true;
            $response["message"] = $e->getMessage();
        }
        break;
    case "POST":
        //create
        break;
    case "GET":
        //read
        try {
            $tabla = $_GET["table"] ?? null;
            if (!empty($tabla)) {
                $aktualis = Tables::getClassByName($tabla, $_GET);
                if (count($aktualis->getNotNulls()) !== 0) {
                    $response["data"] = $db->listazasHaEgyenlo($tabla, $aktualis);
                }
                else{
                    $response["data"] = $db->listazas($tabla);
                }
                //TODO osztályhoz párosítás, majd kulcs/érték szerint
                //megkeresni az összes beállított paramétert, majd
                //mehet a buli
            } else {
                $response["error"] = true;
                $response["message"] = "Nincs kiválasztott tábla!";
            }
        } catch (Error $e) {
            $response["error"] = true;
            $response["message"] = $e->getMessage();
        }
        break;
    case "PUT":
        $table = $_GET["table"] ?? null;
        if ($table !== null) {
            $response["error"] = false;
            $response["message"] = Tables::getClassByName($table)->getKeys();
        }
        //update
        break;
    case "DELETE":
        //delete
        $id = $_GET["id"] ?? null;
        $table = $_GET["table"] ?? null;
        break;
    default:
        $response["error"] = true;
        $response["message"] = "Nem deffiniált kérési metódus!";
        break;
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
