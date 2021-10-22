<?php
header("Content-type: application/json");
require_once "Adatbazis.php";
$db = new Adatbazis();
$response = array();
$response["error"] = false;
switch ($_GET["method"] ?? $_POST["method"] ?? "empty") {
    case "info":
        try{
            $tabla = $_GET["table"] ?? $_POST["table"] ?? null;
            if (!empty($tabla)) {
                if ($tabla==="*"){
                    $response["tables"] = $db->info($tabla);
                }
                else{
                    $response["table"] = $tabla;
                    $response["columns"] = $db->info($tabla);
                }
            }
            else{
                $response["error"] = true;
                $response["message"] = "Nincs kiválasztott tábla!";
            }
        }
        catch (Error $e){
            $response["error"] = true;
            $response["message"] = $e->getMessage();
        }
        break;
    case "create":
        
        break;
    case "read":
        try{
            $tabla = $_GET["table"] ?? $_POST["table"] ?? null;
            if (!empty($tabla)) {
                if (isset($_GET["id"]) || isset($_POST["id"])){
                    $egyezes = (object) array("id"=> $_GET["id"]??$_POST["id"]);
                    $response["data"] = $db->listazasHaEgyenlo($tabla,$egyezes);
                    //TODO osztályhoz párosítás, majd kulcs/érték szerint
                    //megkeresni az összes beállított paramétert, majd
                    //mehet a buli
                }
                else{
                    $response["data"] = $db->listazas($tabla);
                }
            }
            else{
                $response["error"] = true;
                $response["message"] = "Nincs kiválasztott tábla!";
            }
        }
        catch (Error $e){
            $response["error"] = true;
            $response["message"] = $e->getMessage();
        }
        break;
    case "update":

        break;
    case "delete":

        break;
    default:
        $response["error"] = true;
        $response["message"] = "Nem megfelelő paraméterek!";
        break;
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
