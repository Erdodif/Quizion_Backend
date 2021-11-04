<?php
require_once "tables/Tables.php";
class Adatbazis
{
    private static $host = "quizion.hu";//localhost vagy 127.0.0.1
    private static $dbname = "quizion";
    private static $allowedTables = ["quiz", "question", "answer"];

    private $conn;
    private $user = "user";
    private $password = "averagequizionenjoyer";

    public function __construct()
    {
        $host = Adatbazis::$host;
        $dbname = Adatbazis::$dbname;
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->conn = new PDO($dsn, $this->user, $this->password, $options);
    }

    public function info($table)
    {
        $sql = "SHOW COLUMNS FROM $table";
        $out = [];
        if (in_array($table, Adatbazis::$allowedTables)) {
            $result = $this->conn->query($sql);
            while ($row = $result->fetch()) {
                $out[] = $row["Field"];
            }
        } else if ($table === "*") {
            $sql = "SHOW TABLES FROM quizion";
            $result = $this->conn->query($sql);
            while ($row = $result->fetch()) {
                $out[] = $row["Tables_in_quizion"];
            }
        } else {
            throw new Error("Nem található tábla");
        }
        return $out;
    }

    public function listazas($table)
    {
        $sql = "SELECT * FROM $table";
        $result = $this->conn->query($sql);
        return $result->fetchAll();
    }

    public function listazasHaEgyenlo($table,Tables $object)
    {
        $feltetel = "";
        $keresendo = $object->getNotNulls();
        foreach($keresendo as $key => $value){
            $feltetel.="`$key` = :$key AND ";
        }
        $feltetel = mb_substr($feltetel,0,mb_strlen($feltetel)-4);
        $sql = "SELECT * FROM `$table` WHERE $feltetel;";
        /*
        echo var_dump($sql);
        echo var_dump($object);
        echo var_dump($keresendo);*/
        $stmt = $this->conn->prepare($sql);
        foreach($keresendo as $key => $value){
            if(is_numeric($value)){
                $type = PDO::PARAM_INT;
            }
            else{
                $type = PDO::PARAM_STR;
            }
            $stmt->bindParam($key,$value,$type);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function felvetel($table, $object)
    {
        //TODO
        $values = "(";
        foreach ($object as $key) {
            $values = "?,";
        }
        $values = mb_strcut($values, 0, mb_strlen($values) - 1) . ")";
        $sql = "INSERT INTO $table (header,description,active) VALUES $values";
        $stmt = $this->conn->prepare($sql);
        foreach ($object as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        $siker = $stmt->execute();
    }

    public function getObject(string $name, array $content)
    {
        return Tables::getClassByName($name, $content);
    }
}
