<?php

session_start();

require_once "config.php";

header("Content-Type: application/json; charset=utf-8");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {

    echo json_encode([
        "success" => false,
        "message" => "Данные не получены."
    ]);

    exit;
}

$email = trim($data["email"]);
$password = trim($data["password"]);

$stmt = $conn->prepare("SELECT * FROM aregistr WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {

    echo json_encode([
        "success" => false,
        "message" => "Пользователь не найден."
    ]);

    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user["password"])) {

    echo json_encode([
        "success" => false,
        "message" => "Неверный пароль."
    ]);

    exit;
}

// Авторизация

$_SESSION["id"] = $user["id"];
$_SESSION["name"] = $user["name"];
$_SESSION["role"] = $user["Role"];

echo json_encode([
    "success" => true,
    "redirect" => "index.html"
]);