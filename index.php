<?php
header("Content-Type: application/json");

require_once "./config/Database.php";
require_once "./config/Auth.php";

//Функция для установки ответа с ошибкой
function errorResponse(int $code, $message)
{
    http_response_code($code);
    echo json_encode(["message" => $message]);
}

$method = $_SERVER["REQUEST_METHOD"];
$api_key = getallheaders()["Api"];
$url_fragments = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));

$conn = (new Database(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD")))->getConnection();
// Проверка подключения к БД
if ($conn == null) {
    errorResponse(500, "Server DB is not available");
    exit();
}

$auth = new Auth($api_key, $conn);
//Проверка ключа api
if ($auth->check_api_key()) {
    $chg_user = $auth->getId();

    switch ($method) {
        case "POST":
            $data = json_decode(file_get_contents("php://input"), true);

            //Если неверное тело запроса
            if ($data == null) {
                errorResponse(400, "Not appropriate request body");
                exit();
            } elseif (!isset(
                $data["title"],
                $data["instructor"],
                $data["duration_hours"],
                $data["price"]
            )) {
                errorResponse(400, "Necessary params are missed");
                exit();
            }

            //Получение будущего id для последующего возврата клиенту новой записи
            $new_id = null;
            $conn->query("SET information_schema_stats_expiry = 0"); //для обновления системной таблицы
            $auto_inc = $conn->query("SELECT AUTO_INCREMENT
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = 'api_db' AND TABLE_NAME = 'courses'");
            $new_id = ($auto_inc->fetch(PDO::FETCH_ASSOC))["AUTO_INCREMENT"];

            try {
                $stmt = $conn->prepare(
                    "INSERT courses (title, instructor, duration_hours, price, change_user) 
                    VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->bindParam(1, $data["title"], PDO::PARAM_STR);
                $stmt->bindParam(2, $data["instructor"], PDO::PARAM_STR);
                $stmt->bindParam(3, $data["duration_hours"], PDO::PARAM_INT);
                $stmt->bindParam(4, $data["price"]);
                $stmt->bindParam(5, $chg_user, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                errorResponse(400, "Unapproriate values of params");
                exit();
            }

            $new_data = $conn->query("SELECT * FROM courses WHERE id = " . $new_id)->fetch(PDO::FETCH_ASSOC);

            http_response_code(201);
            echo json_encode([
                "message" => "Course added successfully",
                "course" => array(
                    "id" => $new_data["id"],
                    "title" => $new_data["title"],
                    "instructor" => $new_data["instructor"],
                    "duration_hours" => $new_data["duration_hours"],
                    "price" => $new_data["price"],
                    "change_user" => $new_data["change_user"]
                )
            ]);
            break;

        case "GET":

            break;

        case "PUT":
            $data = json_decode(file_get_contents("php://input"), true);
            break;

        case "DELETE":

            break;

        default:
            errorResponse(405, "Method " . $method . "is not allowed");
            exit();
    }
} else {
    errorResponse(401, "Not valid API key");
    exit();
}
