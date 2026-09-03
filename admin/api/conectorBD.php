<?php

class conector
{
    private $servername;
    private $database;
    private $username;
    private $password;
    private static $connection = null;
    public function __construct()
    {
        $this->servername = "localhost";
        $this->database = "iohanes_ojo";
        // $this->username = "conector";
        // $this->password = 'Conecta20255$';
         $this->username = "root";
        $this->password = 'chicle90';
    }
    private function conectar()
    {
        if (self::$connection instanceof mysqli) {
            return self::$connection;
        }

        self::$connection = mysqli_connect(
            $this->servername,
            $this->username,
            $this->password,
            $this->database
        );

        if (!self::$connection) {
            throw new RuntimeException('No se pudo conectar a la base de datos: ' . mysqli_connect_error());
        }

        mysqli_set_charset(self::$connection, 'utf8mb4');
        return self::$connection;
    }

    public function ejecutar($query)
    {
        return mysqli_query($this->conectar(), $query);
    }

    public function ejecutarPreparado($query, $types = '', ...$params)
    {
        $statement = mysqli_prepare($this->conectar(), $query);

        if ($types !== '') {
            mysqli_stmt_bind_param($statement, $types, ...$params);
        }

        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);

        return $result === false ? true : $result;
    }

    public function ultimoIdInsertado()
    {
        return mysqli_insert_id($this->conectar());
    }

    public function iniciarTransaccion()
    {
        mysqli_begin_transaction($this->conectar());
    }

    public function confirmarTransaccion()
    {
        mysqli_commit($this->conectar());
    }

    public function revertirTransaccion()
    {
        mysqli_rollback($this->conectar());
    }
}
