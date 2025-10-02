<?php
require_once "./config/Database.php";
require_once "./config/Auth.php";

$conn = getConn();


$str = $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . " -> ";
$str = $str . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $res);
$str2 = "";
foreach ($res as $param) {
    $str2 = $str2.$param." ";
}
$hash = password_hash("qwerty1234", PASSWORD_BCRYPT);
$b = password_verify("qerty1234", $hash);
if ($b == false){
    echo "false";
} else{
    echo "true";
}
