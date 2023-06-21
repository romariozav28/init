<?php
session_start();

//сценарий авторизации на сайте
require_once ('init.php');
require_once ('function.php');
require_once ('start_user.php');
require_once ('extract_db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {//проверяем что установлен метод POST

    $bet = filter_input(INPUT_POST, 'cost', FILTER_DEFAULT);//принимаем значение ставки из lot.php
    $lot_id = filter_input(INPUT_POST, 'lot_id', FILTER_DEFAULT);//принимаем значение id лота из lot.php
    $min_bet = filter_input(INPUT_POST, 'min_bet', FILTER_DEFAULT);
    $user = $_SESSION['id'];//принимаем значение id пользователя данной сессии

    if ($bet<$min_bet) {
        header ("Location:/lot.php ? id=" . $lot_id . " & classerror=Ставка не может быть меньше минимальной");
        
    } else



        $bet_lot = [$lot_id, $user, $bet];//формируем массив значений из принятых значений для формирования sql запроса

        $sql_input_bet='INSERT INTO bet_lot (lot_id, user_id, bet_user_price) VALUES (?, ?, ?);';//формируем выражение для подготовки подготовленного sql запроса
        $stmt = db_get_prepare_stmt($link, $sql_input_bet, $bet_lot);//подготавливаем подготовленное выражение

        $res=mysqli_stmt_execute($stmt);//выполняется заранее подготовленное выражение, которое берется из $stmt

        if ($res) {//если запрос SQL возвращает TRUE то переходим на страницу my_bets.php
            header("Location:/my_bets.php");//переход на страницу my_bets.php
        } 
        else {$error = mysqli_error($link);//если SQL запрос возвращает FALSE то выдает ошибку
        }
    }