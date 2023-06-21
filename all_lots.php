<?php

session_start();

require_once("function.php");
require_once("init.php");
require_once("start_user.php");
require_once ('extract_db.php');

$id_lot = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$pages = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

var_dump($pages);

$count_show_lots = 6;
$res_lot = extract_all_lots($id_lot, $link);
$count_page = ceil(count($res_lot)/$count_show_lots);
$cats_id = $res_category[$id_lot-1]['category_name'];
$all_count_lot = count($res_lot);

if ($pages) {
    $res_lots = array_slice($res_lot, ($pages-1)*$count_show_lots, $count_show_lots);
}
else {
    $pages = 1;
    $res_lots = array_slice($res_lot, ($pages-1), $count_show_lots);
}

var_dump($pages);
if(!$res_lot) {
    http_response_code(404);
    $page_content = include_template('error.php', [
        'error' => http_response_code(),
        'categorylist' => $res_category
    ]);
}

else{

    var_dump($pages);
$page_content = include_template ('main_all_lots.php', [
        "goodlist" => $res_lots,
        "category_id" => $cats_id,
        "categorylist" => $res_category,
        "is_auth" => $is_auth, 
        "id_lot" => $id_lot,
        "count_page" => $count_page,
        "pages" => $pages,
        "all_count_lot" => $all_count_lot
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