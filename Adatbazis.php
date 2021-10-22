<?php
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

    public function info($tabla)
    {
        $sql = "SHOW COLUMNS FROM $tabla";
        if (!in_array($tabla, $this->allowedTables)) {
            throw new Error("Nem található tábla");
        }
        $result = $this->conn->query($sql);
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row["Field"];
        }
        return $columns;
    }

    private function muvelet($sor, $tabla, $param = null, $kellvissza = false)
    {
        if (!in_array($tabla, $this->allowedTables)) {
            throw new Error("Nem található tábla");
        }
        if ($param === null) {
            $result = $this->conn->query($sor);
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $stmt = $this->conn->prepare($sor);
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

    public function listazas($tabla)
    {
        $sql = "SELECT * FROM $tabla";
        return $this->muvelet($sql, $tabla);
    }

    public function listazasHaEgyenlo($tabla, object $feltetelek)
    {
        $feltetel = "";
        $meret = 0;
        foreach ($feltetelek as $key) {
            $meret++;
        }
        $i = 0;
        foreach ($feltetelek as $key => $value) {
            $i++;
            $key = mysqli_real_escape_string($this->conn, $key);
            $value = mysqli_real_escape_string($this->conn, $value);
            $feltetel .= $key . "=" . $value;
            if ($i < $meret) {
                $feltetel .= " & ";
            }
        }
        $sql = "SELECT * FROM $tabla WHERE $feltetel";
        return $this->muvelet($sql, $tabla);
    }

    public function felvetel($tabla, $objektum)
    {
        $values = "(";
        foreach ($objektum as $key) {
            $values = "?,";
        }
        $values = mb_strcut($values, 0, mb_strlen($values) - 1) . ")";
        $sql = "INSERT INTO $tabla (header,description,active) VALUES $values";
        return $this->muvelet($sql, $objektum);
    }
}
