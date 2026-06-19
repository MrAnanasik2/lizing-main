<?php
header("Content-Type: application/json");

require_once "config.php";

// Получаем JSON из fetch()
$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data["name"]);
$secondname = trim($data["secondname"]);
$phone = trim($data["phone"]);
$email = trim($data["email"]);
$password = $data["password"];

// Проверяем заполнение
if (empty($name) || empty($secondname) || empty($phone) || empty($email) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "Заполните все поля"
    ]);
    exit;
}

// Проверяем, существует ли пользователь
$stmt = $conn->prepare("SELECT id FROM aregistr WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Пользователь с таким E-mail уже существует"
    ]);
    exit;
}

// Шифруем пароль
$hash = password_hash($password, PASSWORD_DEFAULT);

// Добавляем пользователя
$stmt = $conn->prepare("
INSERT INTO aregistr
(name, secondname, phone, email, password)
VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssss",
    $name,
    $secondname,
    $phone,
    $email,
    $hash
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Ошибка регистрации"
    ]);
}

$stmt->close();
$conn->close();