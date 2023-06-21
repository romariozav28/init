<?php
session_start();

require_once ("init.php");
require_once ("function.php");
require_once ("start_user.php");
require_once ("extract_db.php");

//Переменная переданная из URL по запросу с предыдущей страницы
$search  = filter_input(INPUT_GET, 'search');
$goodlist = extract_search($search, $link);
$pages = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

$count_show_lots = 6;
$count_page = ceil(count($goodlist)/$count_show_lots);
$all_count_lot = count($goodlist);

if ($pages) {
    $res_lots = array_slice($goodlist, ($pages-1)*$count_show_lots, $count_show_lots);
}
else {
    $pages = 1;
    $res_lots = array_slice($goodlist, ($pages-1), $count_show_lots);
}

$page_content = include_template ('main_search.php', [
        "request" => $search,
        "goodlist" => $res_lots,
        "categorylist" => $res_category,
        "is_auth" => $is_auth, 
        "pages" => $pages,
        "count_page" => $count_page,
        "all_count_lot" => $all_count_lot
    ]);

$layout_content = include_template ("layout.php", [
    "content" => $page_content,
    "categorylist" => $res_category,
    "title" => "Поиск",
    "is_auth" => $is_auth,
    "user_name" => $user_name
    
    ]);
    
print($layout_content);