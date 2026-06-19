<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Настройки базы данных
$host = "localhost";
$user = "root";
$password = "";
$dbname = "Leazing";

try {

    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {

    header("Content-Type: application/json; charset=utf-8");

    echo json_encode([
        "success" => false,
        "message" => "Не удалось подключиться к базе данных.",
        "error" => $e->getMessage()
    ]);

    exit;
}