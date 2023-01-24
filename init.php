<?php

require_once ('db.php');

$link=mysqli_connect ($db['host'], $db['user'], $db['password'], $db['db']);
mysqli_set_charset($link, 'utf8');

if (!$link) {
    $error=include_template("error.php", [
    "error_link" => "Ошибка подключения: " . mysqli_connect_error()   
    ]);
}
else {
    $error= "Соединение с базой данных установлено";   
}

