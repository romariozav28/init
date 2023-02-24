<?php

require_once ("init.php");
require_once ("function.php");
require_once ("start_user.php");

//Переменная переданная из URL по запросу с предыдущей страницы
$search  = filter_input(INPUT_GET, 'search');

var_dump($search);




if(!$res_category) {
    http_response_code(404);
    $page_content = include_template('error.php', [
        'error' => http_response_code(),
        'categorylist' => $res_category
    ]);
}

else{
$page_content = include_template ('main_search.php', [
       
        "categorylist" => $res_category,
        "is_auth" => $is_auth, 
    ]);
}
$layout_content = include_template ("layout.php", [
    "content" => $page_content,
    "categorylist" => $res_category,
    "title" => "Поиск",
    "is_auth" => $is_auth,
    
    ]);
    
print($layout_content);