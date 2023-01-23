<?php

require_once("function.php");
require_once("init.php");


$page_content = include_template("main.php", [
    "categorylist" => get_arrays_lot($con),
    "category" => get_array_category($con)
]);

$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "category" => get_array_category($con),
    "title" => "Главная",
    "error"=>$error
    
]);

print($layout_content);
