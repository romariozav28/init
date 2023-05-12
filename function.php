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

        var_dump($types);

        $values = array_merge([$stmt, $types], $stmt_data);

        var_dump($values);

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
 * Возвращает количество целых часов и остатка минут от настоящего времени
 * @param string $date Дата истечения времени
 * @return array 
 */
 function get_dt_range ($date) {
    date_default_timezone_set('Europe/Moscow'); //устанавливаем часовую зону
    $final_date = date_create($date);//устанавливаем переменную финальной даты
    $cur_date = date_create("now"); //переменная устанавливающая текущую дату
    $diff = date_diff($final_date, $cur_date);//устанавливаем разницу между финальной датой и текущей
    $format_diff = date_interval_format($diff, "%d %H %I");// устанавливаем формат разницы(переменной diff)
    $arr = explode(" ", $format_diff);//разбиваем строку с помощью разделителя

    $hours = $arr[0]*24 + $arr[1]; 
    $minutes = intval($arr[2]); //возвращает целое значение переменной
    $hours = str_pad($hours, 2, "0", STR_PAD_LEFT); //Дополняет строку другой строкой (значением) до заданной длины
    $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT); //Дополняет строку другой строкой (значением) до заданной длины
    $res[] = $hours;
    $res[] = $minutes;

    return $res;
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


function validateCategory ($id, $allowed_list) { 
    if (!in_array($id, $allowed_list)) { //in_array проверяет присутствует ли в массиве значение переменной id  в списке значений переменной allowed_list
        return "Указана несуществующая категория";
    }
    return null;
} 



/**Проверка на валидацию длины заполнненого значения в форме
 */
function validateLength ($value, $min, $max) {
    if($value) {
        $len = strlen($value); //Возвращает длину строки
            if($len < $min or $len > $max) {
                return "Значение должно быть от $min до $max символов";
            } 
    }
    return null;
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





function validateEmailAuthorisation ($email, $showEmails) {
    if (in_array ($email, $showEmails)) {
        return "Пользователь с таким email уже существует, введите уникальный email";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    }
    
    else { return NULL;}
};

function validateUser ($user, $showUsers) {
    if (!in_array($user, $showUsers)) {
        return NULL;
    } else {
        return "Пользователь с таким именем уже существует, введите уникальное имя";
    }
};

function validateEmailLogin ($email, $showEmails) {
    if  (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    } else if (!in_array ($email, $showEmails)) {
        return "Пользователь с этим email не зарегистрирован, введите другой email";
    } 
    
    else { return NULL;}
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