<?php
require_once "Database.php";

class Auth
{
    private $id = null;
    private $api_key;
    private $conn;

    function __construct($api_key, $conn)
    {
        $this->api_key = $api_key;
        $this->conn = $conn;
    }

    function check_api_key($key)
    {
        if ($this->conn != null) {
            $data = $this->conn->query("SELECT id, api_key FROM api_keys WHERE is_active = TRUE");
            while ($this->id == null && $str = $data->fetch()) {
                if (password_verify($this->api_key, $str["api_key"])) {
                    $this->id = $str["id"];
                }
            }
        }
        return $this->id;
    }
}
