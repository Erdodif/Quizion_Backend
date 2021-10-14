<?php
class Adatbazis
{
    private $host = "localhost";
    private $user = "mobile_user";
    private $password = "averagemobileenjoyer";
    private $dbname = "quizion_temp";
    private $conn;
    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        $this->conn->set_charset("utf8");
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
