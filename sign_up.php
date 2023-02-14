<?php

require_once ('init.php');
require_once ('function.php');
require_once ('start_user.php');

$sql_category="SELECT c.id, c.category_name
FROM category c";
//Извлекаем данные в массив из БД по запросу переменной SQL_CATEGORY
$res_category=get_arrays_DB($link, $sql_category);
if(!$res_category) {
    $page_content = include_template("error.php", [
        "error" => mysqli_connect_error()
    ]);
}
//формируем шаблон main_sign_up.php для подключения к сценарию sign_up.php, на данный момент состоящий только из списка незаполненных форм и включенных в них значений переменной res_category
$page_content = include_template("main_sign_up.php", [
    "categorylist" => $res_category,
    "sign_up" => ['email'=>"", 'password'=>"", 'name'=>"", 'message'=>""]
]);

$sql_email_and_user = "SELECT user_email, user_name FROM user";
$res_email_and_user=get_arrays_DB($link, $sql_email_and_user);
if(!$res_email_and_user) {
    $page_content = include_template("error.php", [
        "error" => mysqli_connect_error()
    ]);
}
$emails = array_column($res_email_and_user, 'user_email');
$users = array_column($res_email_and_user, 'user_name');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required = ['email', 'password', 'name', 'message'];
    $errors = [];

    $rules = [
        "email" => function ($value) use ($emails) {
            return validateEmailAuthorisation($value, $emails);
        },
        "password" => function ($value) {
            return validateLength($value, 8, 250);
        },
        "name" => function($value) use ($users) {
            return validateUser($value, $users);
        },
        "message" => function ($value) {
            return validateLength($value, 15, 3000);
        }
    ];

    $sign_up = filter_input_array(INPUT_POST, ["email" => FILTER_DEFAULT, "password" => FILTER_DEFAULT, "name" => FILTER_DEFAULT, "message" => FILTER_DEFAULT], true);
    
    if (!empty($sign_up['password'])){
        $sign_up['password'] = password_hash($sign_up['password'], PASSWORD_DEFAULT);
    }

    foreach ($sign_up as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        if(in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    $errors = array_filter($errors);

    if (count($errors)) {//если хотя бы одна запись отсутствует
        $page_content = include_template("main_sign_up.php", [//шаблон main_add формируется исходя из этих данных, т.е. добавляются элементы error
            "categorylist" => $res_category,
            "sign_up" => $sign_up,
            "errors" => $errors
        ]);
    } 
    else {
        $sql_sign_up='INSERT INTO user (user_email, user_password, user_name, user_contact) VALUES (?, ?, ?, ?);';
        $stmt = db_get_prepare_stmt($link, $sql_sign_up, $sign_up);
        $res=mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location:/login.php");
        } 
        else {$error = mysqli_error($link);
        }
    }
}

$layout_content = include_template ("layout.php", [
    "content" => $page_content,
    "categorylist" => $res_category,
    "title" => "Регистрация",
    "is_auth" => $is_auth
    ]);
    
print($layout_content);