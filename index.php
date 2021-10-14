<?php
require_once "Adatbazis.php";
$db = new Adatbazis();
$response = array();
$response["error"] = false;
switch ($_GET["method"] ?? "empty") {
    case 'listaz':
        if (isset($_GET["tabla"]) || isset($_POST["tabla"])) {
            echo var_dump($db->listazas($_GET["tabla"] ?? $_POST["tabla"]));
        }
        else{
            $response["error"] = true;
            $response["message"] = "Nincs kiválasztott tábla!";
        }
        break;
    case "felvesz":

        break;
    default:
        $response["error"] = true;
        $response["message"] = "Nem megfelelő paraméterek!";
        break;
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
