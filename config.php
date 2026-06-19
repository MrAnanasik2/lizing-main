<?php
// ================================
// ВПИШИТЕ СВОИ ДАННЫЕ ОТ БАЗЫ
// ================================
$host = "tompsons.beget.tech";             // Обычно localhost
$user = "tompsons_stud01";       // Пользователь MySQL
$password = "1339Roma";         // Пароль MySQL
$dbname = "tompsons_stud01";      // Имя базы данных
// ================================

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Ошибка подключения к базе данных"
    ]));
}

$conn->set_charset("utf8");
?>