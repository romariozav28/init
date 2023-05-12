<?php
session_start();

require_once("function.php");
require_once("init.php");
require_once("start_user.php");
require_once ('extract_db.php');


$page_content = include_template("main.php", [
    "goodlist" => $res_lot_index,
    "categorylist" => $res_category    
]);

$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categorylist" => $res_category,
    "title" => "Главная",
    "is_auth" => $is_auth,
   
    
    
]);

print($layout_content);


