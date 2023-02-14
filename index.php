<?php
session_start();

require_once("function.php");
require_once("init.php");
require_once("start_user.php");


$sql_lot="SELECT l.id, l.lot_image, l.category_id, c.category_name, l.lot_name, l.lot_price_start, l.lot_date_end 
    FROM lot l
    JOIN category c
    ON  l.category_id=c.id 
    WHERE TIMESTAMPDIFF(HOUR, l.lot_date_end, CURDATE()) < 0
    ORDER BY l.lot_date_registration DESC
    LIMIT 6";

$sql_category="SELECT c.category_symbol_code, c.category_name  
    FROM category c";

$page_content = include_template("main.php", [
    "goodlist" => get_arrays_DB($link, $sql_lot),
    "categorylist" => get_arrays_DB($link, $sql_category)    
]);

$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categorylist" => get_arrays_DB($link, $sql_category),
    "title" => "Главная",
    "is_auth" => $is_auth,
    
    
]);

print($layout_content);


