<?php
require_once "Database.php";

function check_api_key($key)
{
    $conn = getConn();
    $api_id = null;

    if ($conn != null) {
        $data = $conn->query("SELECT id, api_key FROM api_keys WHERE is_active = TRUE");
        while ($api_id == null && $str = $data->fetch()) {
            if (password_verify($key, $str["api_key"])) {
                $api_id = $str["id"];
            }
        }
    }

    return $api_id;
}
