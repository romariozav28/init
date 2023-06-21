<?php

/**
     * Форматирует цену
     * @param number $price - изначальная цена 
     * @return string - отформатированная цена
     */

function format_price ($num) {
    $num = ceil($num); //округляет число с плавающей запятой до следующего целого числа
    $num = number_format($num, 0, '', ' '); //приводит к заданному формату
    return $num . " " . "₽"; //выдает конечный оформленный результат в виде 111 111 ₽ 
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }
    
    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';
            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }
            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        mysqli_stmt_bind_param(...$values);      

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}
    

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;
        case ($mod10 > 5):
            return $many;
        case ($mod10 === 1):
            return $one;
        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;
        default:
            return $many;
    }
}
/**Формирует строку с названием файла шаблона и форматирует данные из массива в строковый вид для подключения шаблона в сценарий в виде разметки
*/
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;//
    $result = '';//

    if (!is_readable($name)) {//
        return $result;//
    }

    ob_start();//
    extract($data);//
    require $name;//

    $result = ob_get_clean();//

    return $result;//
}



/**
  * ЗАПРОС ИЗ БазыДанных
  *Запрос из базы данных по SQL-запросу - $SQL, возвращает при помощи SELECT данные из БД yetycave и возвращает массив с данными 
  */
function get_arrays_DB ($connection, $sql) {
    $sql = $sql; 

    $db_result = mysqli_query($connection, $sql);
    if(!$db_result){
        $result="Ошибка SQL" . mysqli_error($connection);        
    } else {
    $result=mysqli_fetch_all($db_result, MYSQLI_ASSOC);
    return $result;
    }
}



/**Проверка на валидацию заполнено ли значение из списка категории
 */
function validateCategory ($id, $allowed_list) {//пользовательская функция
    if (!in_array($id, $allowed_list)) { //in_array проверяет неприсутствует ли в массиве значение переменной id  в списке значений переменной allowed_list, результат TRUE or FALSE
        return "Указана несуществующая категория";//если выражение истина, т.е. в массиве нет запрашиваемой категории, выводи данное выражение
    }
    return null;//если есть в массиве данная категория, то значение NULL (не присвоено никакого значения)
} 

/**Проверка на валидацию длины заполнненого значения в форме на максимальное и минимальное значение
 */
function validateLength ($value, $min, $max) {//пользовательская функция
    if($value) {//если значение заполнено, если значение не заполнено функция не выполняется
        $len = strlen($value); //Возвращает длину строки значения

        if($len < $min or $len > $max) {//если значение меньше минимального или больше максимального 
            return "Значение должно быть от $min до $max символов";//выводит данное выражение
        } 
    }
    return null;//если значение в рамках минимального и максимального значения то выводит NULLт. е. переменной не присвоено никакого значения
}

/**Принимает переменную извне PHP и, при необходимости, фильтрует её
 */
function getPostVal($name) {
    return filter_input_array(INPUT_POST, $name);//
}

/**
 * Проверяет что содержимое поля является числом больше нуля
 * @param string $num число которое ввел пользователь в форму
 * @return string Текст сообщения об ошибке
 */
function validate_number ($num) {
    if (!empty($num)) {
        if (is_numeric($num) && $num > 0) {
            return NULL;
        }
        else {return "Содержимое поля должно быть целым числом больше ноля";}
    }
};

/**
 * Проверяет что дата окончания торгов не меньше одного дня
 * @param string $date дата которую ввел пользователь в форму
 * @return string Текст сообщения об ошибке
 */
function validate_date ($date) {
    if (is_date_valid($date)) {
        $now = date_create("now");
        $d = date_create($date);
        $diff = date_diff($now, $d);

        $interval = date_interval_format($diff, "%r%d");

        if ($interval < 1) {
            return "Дата должна быть больше текущей не менее чем на один день";
        }
    } else {
        return "Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»";
    }
};




//валидация введенного email, как логина при регистрации c сущестующей базой данных user 
function validateEmailAuthorisation ($email, $showEmails) {//пользовательская функция
    if (in_array ($email, $showEmails)) {//проверяет присутствует ли в массиве $showEmails значение $email
        return "Пользователь с таким email уже существует, введите уникальный email";//если такое значение уже есть в базе данных (массиве) выводит данное сообщение
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {//проверяет корректность email, если значение не по правилам заполнения email
        return "Введите корректный email";//возвращает сообщение
    }
    else { return NULL;}//если значение email уникальное и соответствует правилам заполнения то значение не присваивается
};


//валидация имени пользователя с существующей базой данных пользователя (user)
function validateUser ($user, $showUsers) {//пользовательская функция
    if (!in_array($user, $showUsers)) {//проверяет присутствует ли в массиве $showUser значение $user
        return NULL;//возвращает NULL (т.е. пустую переменную) если пользователя с таким именем не существует
    } else {
        return "Пользователь с таким именем уже существует, введите уникальное имя";//возвращает true и вот такое выражение
    }
};


//валидация введенного email при авторизации на сайте
function validateEmailLogin ($email, $showEmails) {//пользовательская функция
    if  (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";//Фильтрует переменную с помощью определённого фильтра, в данном случае фильтрация по email, если значение не удовлетворяет условиям фильтрации, то возвращает данное выражение
    } else if (!in_array ($email, $showEmails)) {//проверяет присутствует ли в массиве $showEmails значение $email
        return "Пользователь с этим email не зарегистрирован, введите другой email";//если в массиве (в базе данных) нет такого значения возвращает false
    } 
    else { return NULL;}//если в массиве есть такое значение возвращает NULL
};


function get_login ($link, $email) {
    if(!$link) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT id, user_email, user_password, user_name FROM user WHERE user_email = '$email'";
        $result = mysqli_query($link, $sql);
        if($result) {
            if(mysqli_num_rows($result)===1){
                $result_user=mysqli_fetch_assoc($result);
            }else if (mysqli_num_rows($result)>1){
                $result_user=mysqli_fetch_all($result, MYSQLI_ASSOC);
            }
        } else {
            $result_user="Ошибка MySqli" . mysqli_error($link);
        }; 
    }
    return $result_user;
};



/**
 * Возвращает количество целых часов и остатка минут от настоящего времени
 * @param string $date Дата истечения времени
 * @return array 
 */
function get_dt_range_All ($date) {
    //Создаем переменные текущей даты и даты ставки
    date_default_timezone_set('Europe/Samara'); //устанавливаем часовую зону
    $bet_date = date_create($date);//устанавливаем переменную даты ставки
    $cur_date = date_create("now"); //переменная устанавливающая текущую дату

    //Устанавливаем форматы времени для вычисления времени оставшегося по дням, часам, минутам
    $bet_date_format = date_format($bet_date, "Y m d H i");//устанавливаем формат даты ставки год/месяц/день/час/минута
    $cur_date_format = date_format($cur_date, "Y m d H i");//устанавливаем формат текущей даты год/месяц/день/час/минута
    $bet_date_format_month_rus = date_format($bet_date, "d m Y");//устанавливаем формат даты ставки день/месяц/год для отображения месяца в русском варианте

    //Раскладываем в массив формат времени, где каждое значение массива это год, месяц, день, час, минута
    $arr_bet_date = explode(" ", $bet_date_format);//раскладываем в массив дату ставки, где каждый элемент это год/месяц/день/час/минута
    $arr_cur_date = explode(" ", $cur_date_format);//раскладываем в массив текущую дату, где каждый элемент это год/месяц/день/час/минута
    $arr_month = explode(" ", $bet_date_format_month_rus);//раскладываем в массив дату ставки, где каждый элемент это день/месяц/год

    //Рассичтываем разницу между отдельными элементами массива текущей даты и датой ставки для определения сегодня или вчера
    $diff_day = $arr_cur_date[2] - $arr_bet_date[2];//рассчитываем разницу между [днем] ставки и [днем] текущим для определения сегодня или вчера
    $diff_hours = $arr_cur_date[3] - $arr_bet_date[3];//рассчитываем разницу между [часом] ставки и [часом] текущим для определения минут назад или часов назад

    $month_list = [//массив со значениями русского написания месяцев для отображения даты в формате "2 июня 2020"
        1  => 'января',
        2  => 'февраля',
        3  => 'марта',
        4  => 'апреля',
        5  => 'мая', 
        6  => 'июня',
        7  => 'июля',
        8  => 'августа',
        9  => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'декабря'
    ];

    $diff = date_diff($bet_date, $cur_date);//устанавливаем разницу между финальной датой и текущей
    $format_diff = date_interval_format($diff, "%d %H %I");// устанавливаем формат разницы(переменной diff)
    $format_diff_hour = date_interval_format($diff, "%H");//устанавливаем формат разницы в часах
    $arr = explode(" ", $format_diff);//разбиваем строку с помощью разделителя в массив значений, согласно формата

    if ($diff_day === 0 & $diff_hours <= 1) {//если разница в днях равна 0 и разница в часах равна 0, то выводим сколько минут назад
        //*
        //значение типа nuumber умножаем на 1, чтобы избавиться от переднего ноля
        //*
        $res = $arr[2]*1 . get_noun_plural_form($arr[2], " минута ", " минуты ", " минут ") . " назад";//get_noun_plural_form для правильного склонения значения
    }
    elseif ($diff_day === 0 & $diff_hours >= 1 & $diff_hours <= 6) {//если разница в днях равна 0 и если разница в часах больше+равно 1 и меньше+равно 6, то выводим сколько часов назад
       $res = $format_diff_hour*1 . get_noun_plural_form($format_diff_hour, " час ", " часа ", " часов ") . " назад";
    }
    elseif ($diff_day === 0 & $diff_hours > 6) {//если разница в днях равна 0 и разница в часах больше 6 выводим "Сегодня в часы + минуты"
        $res = "Сегодня в " . $arr_bet_date[3]*1 . ":" . $arr_bet_date[4];
    }
    elseif ($diff_day === 1 ) {//если разница в днях равна 1, то выводим "Вчера в часы + минуты"
        $res = "Вчера в " . $arr_bet_date[3]*1 . ":" . $arr_bet_date[4];
    }
    else//во всех остальных случаях выводим дату ставки в формате "21 марта 2020 года"
        $res = $arr_month[0]*1 . " " . $month_list[$arr_month[1]*1] . " " . $arr_month[2] . " года";

    return $res;
}



function get_dt_range_finish ($date) {

    date_default_timezone_set('Europe/Samara');//устанавливаем часовую зону

    $final_date_str = strtotime($date);
    $cur_date_str = strtotime("now");
    $res_str = $final_date_str - $cur_date_str;

    $res_str_days = ($res_str / (3600*24));
    $res_str_hours = ($res_str / (3600));
    $res_str_minutes = ($res_str / (60));

    if ($res_str_days <= 1 & $res_str_days >= 0 & $res_str_hours > 1) {
        $res_finish = intval($res_str_hours*1) . get_noun_plural_form($res_str_hours, " час ", " часа ", " часов ");
        $classname_finish = "";
        $classname_finish_rates = "";
    }
    elseif ($res_str_hours <= 1 & $res_str_hours >= 0 & $res_str_days <= 1 & $res_str_days >= 0) {
        $res_finish = intval($res_str_minutes*1) . get_noun_plural_form($res_str_minutes, " минута ", " минуты ", " минут ");
        $classname_finish = "timer--finishing";
        $classname_finish_rates = "";
    }
    elseif ($res_str < 0) {
        $res_finish = "Торги окончены";
        $classname_finish = "timer--end";
        $classname_finish_rates = "rates__item--end";
    }
    elseif ($res_str_days > 1) {
        $res_finish = intval($res_str_days*1) . get_noun_plural_form($res_str_days, " день ", " дня ", " дней ");
        $classname_finish = "";
        $classname_finish_rates = "";
    }
    
    
    return array ($res_finish, $classname_finish, $classname_finish_rates);
}

function get_dt_range ($date) {

    date_default_timezone_set('Europe/Samara');//устанавливаем часовую зону

    $final_date_str = strtotime($date);
    $cur_date_str = strtotime("now");
    $res_str = $final_date_str - $cur_date_str;

    $res_str_days = ($res_str / (3600*24));
    $res_str_hours = ($res_str / (3600));
    $res_str_minutes = ($res_str / (60));

    if ($res_str_days <= 1 & $res_str_days >= 0 & $res_str_hours > 1) {
        $res_finish = intval($res_str_hours*1) . get_noun_plural_form($res_str_hours, " час ", " часа ", " часов ");
        $classname_finish = "";
        $classname_finish_rates = "";
    }
    elseif ($res_str_hours <= 1 & $res_str_hours >= 0 & $res_str_days <= 1 & $res_str_days >= 0) {
        $res_finish = intval($res_str_minutes*1) . get_noun_plural_form($res_str_minutes, " минута ", " минуты ", " минут ");
        $classname_finish = "timer--finishing";
        $classname_finish_rates = "";
    }
    elseif ($res_str < 0) {
        $res_finish = "Время истекло";
        $classname_finish = "timer--end";
        $classname_finish_rates = "rates__item--end";
    }
    elseif ($res_str_days > 1) {
        $res_finish = intval($res_str_days*1) . get_noun_plural_form($res_str_days, " день ", " дня ", " дней ");
        $classname_finish = "";
        $classname_finish_rates = "";
    }
    
    
    return array ($res_finish, $classname_finish, $classname_finish_rates);
}