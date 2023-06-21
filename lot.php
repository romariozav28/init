<?php

require_once ('function.php');
require_once ('init.php');
require_once ('start_user.php');
require_once ('extract_db.php');
require_once ('bet.php');


//Переменная переданная из URL по запросу с предыдущей страницы
$id_lot = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$class_error = filter_input(INPUT_GET, 'classerror', FILTER_DEFAULT);

$res_lot = extract_lot_id($id_lot, $link);
$start_bet = extract_bet_lot($id_lot, $link);
$res_bet = extract_bet_lot_history($id_lot, $link);
$count_bet = count($res_bet);


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
        "start_bet" => $start_bet,
        "categorylist" => $res_category,
        "is_auth" => $is_auth, 
        "class_error" => $class_error,
        "res_bet" => $res_bet,
        "count_bet" => $count_bet
    ]);
}
$layout_content = include_template ("layout.php", [
    "content" => $page_content,
    "categorylist" => $res_category,
    "title" => "Лот",
    "is_auth" => $is_auth,
    "user_name" => $user_name
    
    ]);
    
print($layout_content);

    
















