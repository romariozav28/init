<?php

require_once ('function.php');
require_once ('init.php');
require_once ('start_user.php');


//Переменная переданная из URL по запросу с предыдущей страницы
$id  = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


//Переменная для запроса из БД информации по лотам
$sql_lot="SELECT l.lot_image, l.category_id, c.category_name, l.lot_description, l.lot_name, l.lot_price_start, l.lot_price_step, l.lot_date_end 
FROM lot l
JOIN category c
ON  l.category_id=c.id 
WHERE l.id=$id";

//Извлекаем данные в массив из БД по запросу переменной SQL_LOT
$res_lot=get_arrays_DB($link, $sql_lot);

//Переменная для запроса из БД информации по категориям
$sql_category="SELECT c.category_symbol_code, c.category_name  
FROM category c";

//Извлекаем данные в массив из БД по запросу переменной SQL_CATEGORY
$res_category=get_arrays_DB($link, $sql_category);


if(!$res_lot) {
    http_response_code(404);
    $page_content = include_template('error.php', [
        'error' => http_response_code(),
        'categorylist' => $res_category
    ]);
}

else{
$page_content = include_template ('main_lot.php', [
        "goodlist" => $res_lot,
        "categorylist" => $res_category,
        "is_auth" => $is_auth, 
    ]);
}
$layout_content = include_template ("layout.php", [
    "content" => $page_content,
    "categorylist" => $res_category,
    "title" => "Лот",
    "is_auth" => $is_auth,
    
    ]);
    
print($layout_content);

    
















