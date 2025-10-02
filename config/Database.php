<?php

class Database
{
    private  $host;
    private  $user;
    private   $password;
    private  $conn = null;

    public function __construct(
        $host = "mysql:host=localhost;dbname=api_db",
        $user = "root",
        $password = "admin"
    ) {
        $this->host = $host;
        $this->password = $password;
        $this->user = $user;
    }


    public function getConnection()
    {
        if ($this->conn == null) {
            try {
                $conn = new PDO($this->host, $this->user, $this->password);
                $this->conn = $conn;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return null;
            }
        }
        return $this->conn;
    }
}
