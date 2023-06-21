<?php

require_once ('function.php');
require_once ('init.php');


//Формируем запрос из БД из таблицы category информацию по категориям для всех страниц
$sql_category="SELECT c.id, c.category_symbol_code, c.category_name  
FROM category c";
//Извлекаем данные в массив из БД category по запросу переменной SQL_CATEGORY
$res_category = get_arrays_DB($link,$sql_category);

//*
//формируем выражение для запроса на извлечение из БД таблицы lot последних 6 лотов для сценария index.php
$sql_lot_index="SELECT l.id, l.lot_image, l.category_id, c.category_name, l.lot_name, l.lot_price_start, l.lot_date_end 
    FROM lot l
    JOIN category c
    ON  l.category_id=c.id 
    WHERE TIMESTAMPDIFF(HOUR, l.lot_date_end, CURDATE()) < 0
    ORDER BY l.lot_date_registration DESC
    LIMIT 6";
//Извлекаем данные в массив из БД lot по запросу переменной SQL_LOT_INDEX
$res_lot_index = get_arrays_DB($link, $sql_lot_index);
//*


//Формируем функцию для извлечения данных из БД таблица lot по запросу из принятого значения параметра id GET запроса для сценария lot.php (данные по лоту)
function extract_lot_id ($id, $link) {
$sql_lot_id = "SELECT l.id, l.lot_image, l.category_id, c.category_name, c.id as cat_id, l.lot_description, l.lot_name, l.lot_price_start, l.lot_price_step, l.lot_date_end 
FROM lot l
INNER JOIN category c
ON  l.category_id=c.id
WHERE l.id=$id ";
$res_lot = get_arrays_DB($link, $sql_lot_id);

return $res_lot;};

//Формируем функцию для извлечения данных из БД таблица bet_lot по запросу из принятого значения параметра id GET запроса для сценария lot.php (данные по ставке)
function extract_bet_lot ($id, $link) {
    $sql_bet_lot = "SELECT id, bet_user_price AS win_price, lot_id, user_id, bet_date_of_placement AS bet_date
    FROM bet_lot
    WHERE lot_id=$id AND bet_user_price = (SELECT MAX(bet_user_price) FROM bet_lot WHERE lot_id=$id)";//lot_id - id лота из таблицы bet_lot (максимальная величина ставки по конкретному лоту)

    $res_bet = get_arrays_DB($link, $sql_bet_lot);

    if($res_bet) {
    $max_bet = array_column($res_bet, 'win_price');
    $max_bet = array_shift($max_bet);
    } 
    else $max_bet=0;
    return $max_bet;
}

//Формируем функцию для извлечения данных из БД таблица bet_lot по запросу из принятого значения параметра id GET запроса для сценария lot.php (данные по ставке для истории ставок)
function extract_bet_lot_history ($id, $link) {
    $sql_bet_lot = "SELECT b.id, b.bet_user_price AS price, b.lot_id, b.user_id, b.bet_date_of_placement AS bet_date, u.id, u.user_name
    FROM bet_lot b
    JOIN user u ON b.user_id=u.id
    WHERE lot_id=$id
    ORDER BY bet_date DESC";//lot_id - id лота из таблицы bet_lot (максимальная величина ставки по конкретному лоту)

    $res_bet = get_arrays_DB($link, $sql_bet_lot);
    
    return $res_bet;
}



//Извлекаем данные из БД таблица user для сценария регистрации на сайте sign_up.php и login.php (для валидации принятых значений с таблицей USER и техническим заданием)
$sql_email_and_user = "SELECT user_email, user_name, id FROM user";
$res_email_and_user=get_arrays_DB($link, $sql_email_and_user);


//Формируем функцию для извлечения данных из БД таблица lot по поисковому запросу из принятого значения поискового запроса для сценария search.php
function extract_search ($search, $link) {
    $sql_search = "SELECT l.id, l.lot_name, l.lot_image, l.lot_price_start, l.lot_date_end, l.category_id, c.category_name
    FROM lot l
    JOIN category c ON l.category_id = c.id
    WHERE 
    MATCH(lot_name, lot_description) 
    AGAINST('$search')";
    $res_search = get_arrays_DB($link, $sql_search);
    return $res_search;
};

//Извлекаем все данные из таблицы lot, которые содержат значения параметра id из запроса GET в поле category_id для сценария all_lots.php
function extract_all_lots ($id, $link) {
    $sql_all_lot_id = "SELECT l.id, l.lot_image, l.category_id, c.category_name, l.lot_description, l.lot_name, l.lot_price_start, l.lot_price_step, l.lot_date_end 
    FROM lot l
    JOIN category c
    ON  l.category_id=c.id 
    WHERE l.category_id=$id AND TIMESTAMPDIFF(HOUR, l.lot_date_end, CURDATE()) < 0
    ORDER BY TIMESTAMPDIFF(HOUR, l.lot_date_end, CURDATE()) DESC";
    $res_all_lot = get_arrays_DB($link, $sql_all_lot_id);

    return $res_all_lot;};


//Формируем функцию для извлечения данных из БД таблица lot по запросу из принятого значения параметра id GET запроса для сценария lot.php
function extract_mylot_id ($id, $link) {
    $sql_bet_id = "SELECT b.bet_user_price, b.user_id as user_lot, b.lot_id, l.lot_name, l.lot_image, u.user_contact as bet_contact, l.category_id, c.category_name, l.user_id, l.id, l.lot_date_end, b.bet_date_of_placement
    FROM bet_lot b
    INNER JOIN lot l
    ON  b.lot_id=l.id
    INNER JOIN user u
    ON l.user_id=u.id
    INNER JOIN category c
    ON l.category_id=c.id 
    WHERE b.user_id=$id
    ORDER BY bet_date_of_placement DESC";

    $res_lot = get_arrays_DB($link, $sql_bet_id);

    
    return $res_lot;};

    