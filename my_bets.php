<?php

session_start();

require_once("function.php");
require_once("init.php");
require_once("start_user.php");
require_once('extract_db.php');

$id=$_SESSION['id'];
$res_lot=extract_mylot_id ($id, $link);



$page_content = include_template ('main_my_bets.php', [
    "goodlist" => $res_lot,
    "categorylist" => $res_category,
    "is_auth" => $is_auth, 
]);

$layout_content = include_template ("layout.php", [
"content" => $page_content,
"categorylist" => $res_category,
"title" => "Лот",
"is_auth" => $is_auth,
"user_name" => $user_name

]);

print($layout_content);