<?php

require_once ('function.php');
require_once ('init.php');


//Формируем запрос из БД из таблицы category информацию по категориям
$sql_category="SELECT c.id, c.category_symbol_code, c.category_name  
FROM category c";
//Извлекаем данные в массив из БД category по запросу переменной SQL_CATEGORY
$res_category = get_arrays_DB($link,$sql_category);

//формируем запрос на извлечение из БД таблицы lot последних 6 лотов
$sql_lot_index="SELECT l.id, l.lot_image, l.category_id, c.category_name, l.lot_name, l.lot_price_start, l.lot_date_end 
    FROM lot l
    JOIN category c
    ON  l.category_id=c.id 
    WHERE TIMESTAMPDIFF(HOUR, l.lot_date_end, CURDATE()) < 0
    ORDER BY l.lot_date_registration DESC
    LIMIT 6";
//Извлекаем данные в массив из БД lot по запросу переменной SQL_LOT_INDEX
$res_lot_index = get_arrays_DB($link, $sql_lot_index);


//Формируем функцию для извлечения данных из БД таблица lot по запросу из принятого значения параметра id GET запроса 
function extract_lot_id ($id, $link) {
$sql_lot_id = "SELECT l.lot_image, l.category_id, c.category_name, l.lot_description, l.lot_name, l.lot_price_start, l.lot_price_step, l.lot_date_end 
FROM lot l
JOIN category c
ON  l.category_id=c.id 
WHERE l.id=$id";
$res_lot = get_arrays_DB($link, $sql_lot_id);
return $res_lot;};

//Извлекаем данные из БД таблица user для сценария регистрации на сайте sign_up.php (для валидации принятых значений с таблицей USER и техническим заданием)
$sql_email_and_user = "SELECT user_email, user_name FROM user";
$res_email_and_user=get_arrays_DB($link, $sql_email_and_user);
