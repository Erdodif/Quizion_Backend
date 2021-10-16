<?php
require_once "Adatbazis.php";
$db = new Adatbazis();
$response = array();
$response["error"] = false;
switch ($_GET["method"] ?? $_POST["method"] ?? "empty") {
    case "create":
        
        break;
    case "read":
        if (isset($_GET["table"]) || isset($_POST["table"])) {
            echo var_dump($db->listazas($_GET["table"] ?? $_POST["table"]));
        }
        else{
            $response["error"] = true;
            $response["message"] = "Nincs kiválasztott tábla!";
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
?>