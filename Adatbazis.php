<?php
class Adatbazis
{
    private $host = "localhost";
    private $user = "user";
    private $password = "averagequizionenjoyer";
    private $dbname = "quizion";
    private $conn;
    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        mysqli_set_charset($this->conn, "utf8mb4");
        if ($this->conn->connect_error) {
            die("Sikertelen kapcsolódás az adatbázissal: " . $this->conn->connect_error);
        }
    }

    private function muvelet($sor, $param = null)
    {
        if ($param === null) {
            $result = $this->conn->query($sor);
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $stmt = $this->conn->prepare($sor);
            foreach ($param as $key => $value) {
                $stmt->bind_param($key, $value);
            }
            return $stmt->execute();
        }
    }

    public function listazas($tabla)
    {
        $sql = "SELECT * FROM $tabla";
        return $this->muvelet($sql);
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
        return $this->muvelet($sql);
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
