<?php

require_once ('function.php');
require_once ('init.php');


$tab  = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_NUMBER_INT);
if($tab) {
    $page_content_lot = include_template('main_lot.php', [
        "categorylist" => get_arrays_lot_items($con, $tab),
        "category" => get_array_category($con),
        
    
    ]);
    
    $layout_content = include_template("layout.php", [
        "content" => $page_content_lot,
        "category" => get_array_category($con),
        "title" => "Лот",
        "error"=>$error
        
    ]);
    
    print($layout_content);
}
else {
    

    http_response_code(404);
    die();

}





var_dump($_GET['tab']);
$result=get_arrays_lot_items($con, $tab);
var_dump($result);



