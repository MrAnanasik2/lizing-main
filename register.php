<?php

require_once "config.php";

header("Content-Type: application/json; charset=utf-8");

// Получаем JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Данные не получены."
    ]);
    exit;
}

$name = trim($data["name"]);
$secondname = trim($data["secondname"]);
$phone = trim($data["phone"]);
$email = trim($data["email"]);
$password = trim($data["password"]);

if (
    empty($name) ||
    empty($secondname) ||
    empty($phone) ||
    empty($email) ||
    empty($password)
) {

    echo json_encode([
        "success" => false,
        "message" => "Заполните все поля."
    ]);

    exit;
}

// Проверяем существование email

$check = $conn->prepare("SELECT id FROM aregistr WHERE email=?");
$check->bind_param("s", $email);
$check->execute();

if ($check->get_result()->num_rows > 0) {

    echo json_encode([
        "success" => false,
        "message" => "Пользователь с таким Email уже существует."
    ]);

    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$role = "user";

$sql = $conn->prepare("
INSERT INTO aregistr
(name, secondname, phone, email, password, Role)
VALUES
(?,?,?,?,?,?)
");

$sql->bind_param(
    "ssssss",
    $name,
    $secondname,
    $phone,
    $email,
    $hash,
    $role
);

if ($sql->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Регистрация успешна."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Ошибка регистрации."
    ]);

}