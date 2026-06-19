<?php
header("Content-Type: application/json");

require_once "config.php";

// Получаем данные из fetch()
$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data["email"]);
$password = $data["password"];

// Проверка заполнения
if (empty($email) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "Введите E-mail и пароль"
    ]);
    exit;
}

// Ищем пользователя
$stmt = $conn->prepare("SELECT id, password, Role FROM aregistr WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Пользователь не найден"
    ]);
    exit;
}

$user = $result->fetch_assoc();

// Проверяем пароль
if (password_verify($password, $user["password"])) {

    session_start();

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["role"] = $user["Role"];

    if ($user["Role"] == "Admin") {

        echo json_encode([
            "success" => true,
            "role" => "Admin",
            "redirect" => "admin.html"
        ]);

    } else {

        echo json_encode([
            "success" => true,
            "role" => "User",
            "redirect" => "catalog.html"
        ]);

    }

}

$stmt->close();
$conn->close();
?>