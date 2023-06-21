<?php
session_start();

require_once ('function.php');
require_once ('init.php');
require_once ('start_user.php');
require_once ('extract_db.php');


if (!$_SESSION) {
    $page_content = include_template("error.php", [
        "categorylist" => $res_category,
        "error" => "Вы не авторизованы. Выполните вход на сайт.",
        
    ]);
} else {

    
    $cats_names=array_column($res_category, 'category_name');//возвращает массив значений из одного столбца входного массива
    $lot = ['lot_name' => '', 'lot_description' => '', 'lot_price_start'=>'', 'lot_price_step'=>'', 'lot_date_end'=>'', 'category_name'=>''];
    $required = ['lot_name', 'lot_description', 'lot_price_start', 'lot_price_step', 'lot_date_end', 'category_name'];//создаем массив, где значения ключей полей являются названиями полей формы, для проверки на заполненность формы
    $cats_ids=array_column($res_category, 'id');//возвращает массив значений из одного столбца входного массива
    $errors = [];//создаем массив для хранения ошибок

//формируем шаблон main_add.php для подключения к сценарию add.php, на данный момент состоящий только из списка незаполненных форм и включенных в них значений переменной res_category
$page_content = include_template("main_add.php", [
    "categorylist" => $res_category,
    "lot" => $lot,
    "cats_names" => $cats_names,
    
]);




    if ($_SERVER['REQUEST_METHOD'] === 'POST') {//проверяем что установлен метод POST

    //создаме массив для хранения валидации данных при заполнения полей значениями
    //в случае успешной валидации возвращает NULL
    //в случае неуспешной валидации возвращает строку с указанием требуемых параметров
    $rules = [  
        'lot_name' => function($value) {
            return validateLength($value, 10, 200);//валидация длины значения имени лота
        },
        'lot_description' => function($value) {
            return validateLength($value, 10, 3000);//валидация длины значения описания лота
        },
        'lot_price_start' => function($value) {
            return validate_number($value);//валидация формата числа значения цены
        },
        'lot_price_step' => function($value) {
            return validate_number($value);//валидация формата числа значения щага цены
        },
        'lot_date_end' => function($value) {
            return validate_date($value);//валидация формата даты
        },
        'category_name' => function($value) use ($cats_ids) {
            return validateCategory($value, $cats_ids);//валидация значения категории, проверяет присутствует ли значение value  в списке cats_ids, где cats_ids принимается внешнее значение функции и приравнивается к массиву cats_ids
        }
    ];

    $lot = filter_input_array(INPUT_POST, ['lot_name' => FILTER_DEFAULT, 'lot_description' => FILTER_DEFAULT, 'lot_price_start' => FILTER_DEFAULT, 'lot_price_step' => FILTER_DEFAULT, 'lot_date_end' => FILTER_DEFAULT,  'category_name' => FILTER_DEFAULT], true); // получаем несколько переменных извне и при необходимости фильтруем их, т.е. при заполнении форм

    
    //проходимся циклом по массиву lot на наличие ошибок в валидации
    foreach ($lot as $key => $value) {
        if (isset($rules[$key])) {//проверяем была ли устанволена переменная отличная от NULL, если да то идем дальше
            $rule = $rules[$key];//переменная rule становится функцией rules (а если по научному объектом) с ключом из переменной lot, т. е. подготавливается функция проверки валидации
            $errors[$key] = $rule($value);//присваивается значение переменной error вычисленной объектом(функцией) rule со значением переданным из переменной lot (либо NULL либо ошибка)
        }

        if(in_array($key, $required) && empty($value)) {//проверяем условие присутствует ли в массиве required значение key и пустая ли переменная value
            $errors[$key] = "Поле $key надо заполнить";//если условия выполняются то переменная в массив errors с ключом key заносится запись "Поле . значение переменной key . надо заполнить"
        }
    }

    
    $errors = array_filter($errors);//фильтрует массив, определяя пустые поля и удаляя их
    

    if (!empty($_FILES["lot_img"]["name"])) {

        $tmp_name = $_FILES["lot_img"]["tmp_name"];
        $path = $_FILES["lot_img"]["name"];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if($file_type === "image/jpeg") {
            $ext = ".jpg";
            $filename = uniqid() . $ext;
            $lot["lot_img"] = "uploads/". $filename;
            move_uploaded_file($_FILES["lot_img"]["tmp_name"], $lot["lot_img"]);
        } elseif ($file_type === "image/png") {
            $ext = ".png";
            $filename = uniqid() . $ext;
            $lot["lot_img"] = "uploads/". $filename;
            move_uploaded_file($_FILES["lot_img"]["tmp_name"], $lot["lot_img"]);
        }
        else {
            $errors["lot_img"] = "Допустимые форматы файлов: jpg, jpeg, png";
        } 
    } 
    else {$errors["lot_img"] = "Вы не загрузили изображение";}

    if (count($errors)) {//если хотя бы одно значение в форме добавления лота отсутствует
        $page_content = include_template("main_add.php", [//шаблон main_add формируется исходя из этих данных, т.е. добавляются элементы error
            "categorylist" => $res_category,
            "lot" => $lot,
            "errors" => $errors,
            "cats_names" => $cats_names,

         ]);

    } 
    else {
        $lot['user_id'] = $_SESSION['id'];
        $sql_input_lot='INSERT INTO lot (lot_name, lot_description, lot_price_start, lot_price_step, lot_date_end, category_id, lot_image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?);';
        $stmt = db_get_prepare_stmt($link, $sql_input_lot, $lot);

        $res=mysqli_stmt_execute($stmt);//выполняется заранее подготовленное выражение, которое берется из $stmt

        if ($res) {
            $lot_id = mysqli_insert_id($link);

            header("Location:/lot.php?id=".$lot_id);
        } 
        else {$error = mysqli_error($link);
        }
    }
} 


} 

$layout_content = include_template ("layout.php", [
    "content" => $page_content,
    "categorylist" => $res_category,
    "title" => "Добавление лота",
    "is_auth" => $is_auth,
    "user_name"=> $user_name,
    
   
    
    ]);
    
print($layout_content);
 