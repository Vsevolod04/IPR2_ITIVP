<?php
//Чтение данных из файла переменных окружения
$lines = file(".env", FILE_SKIP_EMPTY_LINES | FILE_USE_INCLUDE_PATH);
foreach ($lines as $line) {
    [$key, $value] = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value);
    putenv(sprintf('%s=%s', $key, $value));
}

class Database
{
    private  $host;
    private  $user;
    private   $password;
    private  $conn = null;

    public function __construct(
        $host = "host",
        $user = "user",
        $password = "password"
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
                return null;
            }
        }
        return $this->conn;
    }
}
