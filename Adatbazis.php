<?php
require_once "tables/Tables.php";
class Adatbazis
{
    private static $logMode;
    private static $host = "quizion.hu"; //localhost vagy 127.0.0.1
    private static $dbname = "quizion";
    private static $allowedTables = ["quiz", "question", "answer"];

    private $conn;
    private $user = "user";
    private $password = "averagequizionenjoyer";

    protected static function init()
    {
        Adatbazis::$logMode = LOG_MODE_OFF; //LOG_MODE_OFF legyen, ha a használatban van az API!!!
    }

    public function __construct()
    {
        Adatbazis::init();
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
        $sql = "SHOW COLUMNS FROM `$table`";
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

    public function listazas($table): array|bool
    {
        $sql = "SELECT * FROM $table";
        $result = $this->conn->query($sql);
        if (Adatbazis::$logMode) {
            echo "listazasHaEgyenlo->sql parancs:\n";
            echo var_dump($sql) . "\n";
        }
        return $result->fetchAll();
    }

    public function listazasHaEgyenlo($table, Tables $object): array|bool
    {
        $feltetel = "";
        $keresendo = $object->getNotNulls();
        foreach ($keresendo as $key => $value) {
            $feltetel .= "`$key` = :$key AND ";
        }
        $feltetel = mb_substr($feltetel, 0, mb_strlen($feltetel) - 4);
        $sql = "SELECT * FROM `$table` WHERE $feltetel;";
        $stmt = $this->conn->prepare($sql);
        foreach ($keresendo as $key => $value) {
            $stmt->bindParam($key, $value, Adatbazis::getParamType($value));
        }
        $stmt->execute();
        if (Adatbazis::$logMode) {
            echo "listazasHaEgyenlo->kapott object:\n";
            echo var_dump($object) . "\n";
            echo "listazasHaEgyenlo->nem null értékek:\n";
            echo var_dump($keresendo) . "\n";
            echo "listazasHaEgyenlo->sql parancs:\n";
            echo var_dump($sql) . "\n";
            echo "listazasHaEgyenlo->PDOState:\n";
            echo var_dump($stmt) . "\n";
        }
        return $stmt->fetchAll();
    }

    public function felvetel($table, $object)
    {
        $oszlopok = "(";
        $values = "(";
        $keresendo = $object->getNotNulls();
        foreach ($keresendo as $key => $value) {
            $oszlopok .= "`$key`, ";
            $values .= ":$key, ";
        }
        //trim talán
        $values = mb_strcut($values, 0, mb_strlen($values) - 2) . ")";
        $oszlopok = mb_strcut($oszlopok, 0, mb_strlen($oszlopok) - 2) . ")";
        $sql = "INSERT INTO `$table` $oszlopok VALUES $values;";
        $stmt = $this->conn->prepare($sql);
        foreach ($keresendo as $key => $value) {
            $stmt->bindParam($key, $value, Adatbazis::getParamType($value));
        }
        if (Adatbazis::$logMode) {
            echo "felvetel->kapott object:\n";
            echo var_dump($object) . "\n";
            echo "felvetel->kivett értékek:\n";
            echo var_dump($keresendo) . "\n";
            echo "felvetel->sql parancs:\n";
            echo var_dump($sql) . "\n";
            echo "felvetel->átadott PDOStatement:\n";
            echo var_dump($stmt) . "\n";
        }
        return $stmt->execute();
    }

    public function frissit($table, $object)
    {
        $values = "";
        $keresendo = $object->getNotNulls();
        $id = $object->getNotNulls()["id"];
        foreach ($keresendo as $key => $value) {
            if ($key !== "id") {
                $values .= "`$key` = :$key, ";
            }
        }
        //trim esetleg
        $values = mb_strcut($values, 0, mb_strlen($values) - 2);
        $sql = "UPDATE `$table` SET $values WHERE `id` = $id;";
        $stmt = $this->conn->prepare($sql);
        foreach ($keresendo as $key => $value) {
            if ($key !== "id") {
                $stmt->bindValue($key, $value, Adatbazis::getParamType($value));
            }
        }
        if (Adatbazis::$logMode) {
            echo "felvetel->kapott object:\n";
            echo var_dump($object) . "\n";
            echo "felvetel->kivett értékek:\n";
            echo var_dump($keresendo) . "\n";
            echo "felvetel->sql parancs:\n";
            echo var_dump($sql) . "\n";
            echo "felvetel->átadott PDOStatement:\n";
            echo var_dump($stmt) . "\n";
        }
        return $stmt->execute();
    }

    public function torolHaEgyenlo($table, Tables $object)
    {
        $feltetel = "";
        $keresendo = $object->getNotNulls();
        foreach ($keresendo as $key => $value) {
            $feltetel .= "`$key` = :$key AND ";
        }
        $feltetel = mb_substr($feltetel, 0, mb_strlen($feltetel) - 4);
        $sql = "DELETE FROM `$table` WHERE $feltetel;";
        $stmt = $this->conn->prepare($sql);
        foreach ($keresendo as $key => $value) {
            $stmt->bindParam($key, $value, Adatbazis::getParamType($value));
        }
        $stmt->execute();
        if (Adatbazis::$logMode) {
            echo "listazasHaEgyenlo->kapott object:\n";
            echo var_dump($object) . "\n";
            echo "listazasHaEgyenlo->nem null értékek:\n";
            echo var_dump($keresendo) . "\n";
            echo "listazasHaEgyenlo->sql parancs:\n";
            echo var_dump($sql) . "\n";
            echo "listazasHaEgyenlo->PDOState:\n";
            echo var_dump($stmt) . "\n";
        }
        return $stmt->execute();
    }

    static public function getParamType($value)
    {
        if (empty($value)) {
            $type = PDO::PARAM_NULL;
        } else if (is_numeric($value)) {
            $type = PDO::PARAM_INT;
        } else if (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        } else {
            $type = PDO::PARAM_STR;
        }
        return $type;
    }
}
define("LOG_MODE_ON", true);
define("LOG_MODE_OFF", false);
