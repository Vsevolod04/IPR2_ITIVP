<?php
$lines = file(".env", FILE_SKIP_EMPTY_LINES | FILE_USE_INCLUDE_PATH);
       foreach ($lines as $line) {
           [$key, $value] = explode('=', $line, 2);
           $key = trim($key);
           $value = trim($value);

           putenv(sprintf('%s=%s', $key, $value));
       }

function getConn()
{
    $host = getenv("DB_HOST");
    $user = getenv("DB_USER");
    $password = getenv("DB_PASSWORD");
    
    try {
        $conn = new PDO($host, $user, $password);
    } catch (PDOException $e) {
        echo $e->getMessage();
        return null;
    }
    return $conn;
}
