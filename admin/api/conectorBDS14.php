<?php

class conectorS14
{
    private $servername;
    private $database;
    private $username;
    private $password;
    public function __construct()
    {
        $this->servername = "localhost";
        $this->database = "iohanes_14";
        $this->username = "iohanes_14";
        $this->password = 'bSh4_(+L*)sY';
    }
    public function ejecutar($query)
    {
        $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
        if (!$conn) {
            die("No se pudo conectar devido a: " . mysqli_connect_error());
        }
        $result = mysqli_query($conn, $query);
        mysqli_close ($conn);
        return $result;
    }
}
