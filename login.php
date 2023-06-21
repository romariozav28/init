<?php
//сценарий авторизации на сайте
require_once ('init.php');
require_once ('function.php');
require_once ('start_user.php');
require_once ('extract_db.php');


$res_user = $res_email_and_user;//извлекаем данные из базы данных таблица user
if(!$res_user) {
    $page_content = include_template("error.php", [
        "categorylist" => $res_category,
        "error" => mysqli_connect_error()
    ]);
}

$emails = array_column($res_user, 'user_email');

$login = ['email' => '', 'password' => ''];

//формируем шаблон main_login.php для подключения к сценарию login.php, на данный момент состоящий только из списка незаполненных форм и включенных в них пустых значений переменной login
$page_content = include_template("main_login.php", [
    "categorylist" => $res_category,
    "login" => $login
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //Проверка на метод POST

    $required = ['email', 'password'];
    $errors = [];

    $rules = [
        "email" => function ($value) use ($emails) {
            return validateEmailLogin($value, $emails);
        },
        "password" => function ($value) {
            return validateLength($value, 8, 250);
        }
    ];

    $login = filter_input_array(INPUT_POST, ["email" => FILTER_DEFAULT, "password" => FILTER_DEFAULT], true);

    foreach ($login as $key => $value) {
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
        $page_content = include_template("main_login.php", [//шаблон main_add формируется исходя из этих данных, т.е. добавляются элементы error
            "categorylist" => $res_category,
            "login" => $login,
            "errors" => $errors
        ]);
    } else {
        $login_result=get_login($link, $login['email']);
        if(password_verify($login['password'], $login_result['user_password'])){

            session_start();
            $_SESSION['user_name'] = $login_result['user_name'];
            $_SESSION['id'] = $login_result['id'];

            header("Location:/index.php");
        } else {
            $errors['password'] = "Вы ввели неправильный пароль";
            $page_content = include_template("main_login.php", [//шаблон main_add формируется исходя из этих данных, т.е. добавляются элементы error
                "categorylist" => $res_category,
                "login" => $login,
                "errors" => $errors
            ]);
        }

    
}

}

$layout_content = include_template ("layout.php", [
    "content" => $page_content,
    "categorylist" => $res_category,
    "title" => "Авторизация",
    "is_auth" => $is_auth,
    "user_name" => $user_name
   
   
    
    ]);
    
print($layout_content);