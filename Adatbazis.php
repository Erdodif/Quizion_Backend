<?php
require_once "tables/Table.php";
class Adatbazis
{
    private $host = "localhost";
    private $user = "user";
    private $password = "averagequizionenjoyer";
    private $dbname = "quizion";
    private $conn;
    private $allowedTables = ["quiz", "question", "answer"];
    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        mysqli_set_charset($this->conn, "utf8mb4");
        if ($this->conn->connect_error) {
            die("Sikertelen kapcsolódás az adatbázissal: " . $this->conn->connect_error);
        }
    }

    public function info($table)
    {
        $sql = "SHOW COLUMNS FROM $table";
        $out = [];
        if (in_array($table, $this->allowedTables)) {
            $result = $this->conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $out[] = $row["Field"];
            }
        }
        else if($table === "*"){
            $sql = "SHOW TABLES FROM quizion";
            $result = $this->conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $out[] = $row["Tables_in_quizion"];
            }
        }
        else{
            throw new Error("Nem található tábla");
        }
        return $out;
    }

    private function muvelet($row, $table, $param = null, $kellvissza = false)
    {
        if (!in_array($table, $this->allowedTables)) {
            throw new Error("Nem található tábla");
        }
        if ($param === null) {
            $result = $this->conn->query($row);
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $stmt = $this->conn->prepare($row);
            echo $stmt;
            echo var_dump($param);
            foreach ($param as $key => $value) {
                $tipus = "s";
                if (is_numeric($value)) {
                    $tipus = "d";
                }
                echo $value;
                $stmt->bind_param($tipus, $value);
            }
            $siker = $stmt->execute();
            if ($kellvissza) {
                $siker = $stmt->get_result();
            }
            return $siker;
        }
    }

    public function listazas($table)
    {
        $sql = "SELECT * FROM $table";
        return $this->muvelet($sql, $table);
    }

    public function listazasHaEgyenlo($table, object $clause)
    {
        $feltetel = "";
        $meret = 0;
        foreach ($clause as $key) {
            $meret++;
        }
        $i = 0;
        foreach ($clause as $key => $value) {
            $i++;
            $key = mysqli_real_escape_string($this->conn, $key);
            $value = mysqli_real_escape_string($this->conn, $value);
            $feltetel .= $key . "=" . $value;
            if ($i < $meret) {
                $feltetel .= " & ";
            }
        }
        $sql = "SELECT * FROM $table WHERE $feltetel";
        return $this->muvelet($sql, $table);
    }

    public function felvetel($table, $object)
    {
        $values = "(";
        foreach ($object as $key) {
            $values = "?,";
        }
        $values = mb_strcut($values, 0, mb_strlen($values) - 1) . ")";
        $sql = "INSERT INTO $table (header,description,active) VALUES $values";
        return $this->muvelet($sql, $object);
    }
}
