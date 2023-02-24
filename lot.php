<?php

require_once ('function.php');
require_once ('init.php');
require_once ('start_user.php');
require_once ('extract_db.php');


//Переменная переданная из URL по запросу с предыдущей страницы
$id_lot = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$res_lot = extract_lot_id($id_lot, $link);


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

    
















