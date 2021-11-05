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
            $tabla = $_GET["table"] ?? "*";
            if ($tabla === "*") {
                $response["tables"] = $db->info($tabla);
            } else {
                $response["table"] = $tabla;
                $response["columns"] = $db->info($tabla);
            }
        } catch (Error $e) {
            $response["error"] = true;
            $response["message"] = $e->getMessage();
        }
        break;
    case "POST":
        //create
        $tabla = $_POST["table"] ?? null;
        $aktualis = Tables::getClassByName($tabla, $_POST);
        $db->felvetel($tabla, $aktualis);
        break;
    case "GET":
        //read
        try {
            $tabla = $_GET["table"] ?? null;
            if (!empty($tabla)) {
                $aktualis = Tables::getClassByName($tabla, $_GET);
                if (count($aktualis->getNotNulls()) !== 0) {
                    $ki = $db->listazasHaEgyenlo($tabla, $aktualis);
                } else {
                    $ki = $db->listazas($tabla);
                }
                if (count($ki) === 0) {
                    $response["error"] = true;
                    $response["message"] = "Üres találat!";
                } else {
                    $response["data"] = $ki;
                }
            } else {
                $response["error"] = true;
                $response["message"] = "Nincs kiválasztott tábla!";
            }
        } catch (Error $e) {
            //throw $e; //debug céljából
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
        $tabla = $_POST["table"] ?? null;
        $aktualis = Tables::getClassByName($tabla, $_GET);
        $db->torolHaEgyenlo($tabla, $aktualis);
        break;
    default:
        $response["error"] = true;
        $response["message"] = "Nem deffiniált kérési metódus!";
        break;
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
