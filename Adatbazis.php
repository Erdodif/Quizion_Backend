<?php
require_once "tables/Tables.php";
class Adatbazis
{
    private static $host = "localhost";
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
        if (in_array($table, $this->allowedTables)) {
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

    public function listazasHaEgyenlo($table, $params)
    {
        $feltetel = "";
        $kulcsok = Tables::getClassByName($table)->getKeys();
        $sql = "SELECT * FROM $table WHERE $feltetel";
        return /*TODO*/;
    }

    public function felvetel($table, $object)
    {
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

    public function getObject(string $name, object $content)
    {
        return Tables::getClassByName($name, $content);
    }
}
