<?php

/**
     * Форматирует цену
     * @param number $price - изначальная цена 
     * @return string - отформатированная цена
     */

function format_price ($num) {
    $num = ceil($num);
    $num = number_format($num, 0, '', ' ');
    return $num . " " . "₽";
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
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

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
/**
 * 
 * 
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
  * ЗАПРОС ЛОТ
  *Запрос из базы данных лотов, возвращает при помощи SELECT данные по лотам из БД yetycave и возвращает массив с данными 
  */
  function get_arrays_lot ($connection) {
    $sql = "SELECT lot.id, lot.lot_image, lot.category_id, category.category_name, lot.lot_name, lot.lot_price_start, lot.lot_date_end 
    FROM lot 
    JOIN category 
    ON  lot.category_id=category.id 
    ORDER BY lot.lot_date_registration DESC
    LIMIT 6" ; 
    $db_result_lot = mysqli_query($connection, $sql);
    if(!$db_result_lot){
        $error = mysqli_error($connection);
        print("Ошибка MySQL: ".$error);
    }
    $result_lot=mysqli_fetch_all($db_result_lot, MYSQLI_ASSOC);
    return $result_lot;}

    
 
 
 
 
 
 
/**
  *ЗАПРОС КАТЕГОРИИ 
  *Запрос из базы данных категорий, возвращает при помощи SELECT данные по категориям из БД yetycave и возвращает массив с данными 
  */
  function get_array_category($connection) {
    $sql="SELECT category.category_symbol_code, category.category_name  
    FROM category"; 
    $db_result_category = mysqli_query($connection, $sql);
    if(!$db_result_category){
        $error = mysqli_error($connection);
        print("Ошибка MySQL: ".$error);
    }
    $result_category = mysqli_fetch_all($db_result_category, MYSQLI_ASSOC);
    return $result_category;}




/**
  * ЗАПРОС ЛОТ
  *Запрос из базы данных лотов, возвращает при помощи SELECT данные по лотам из БД yetycave и возвращает массив с данными 
  */
  function get_arrays_lot_items ($connection, $item) {
    $sql = "SELECT lot.lot_image, lot.category_id, category.category_name, lot.lot_description, lot.lot_name, lot.lot_price_start, lot.lot_date_end 
    FROM lot 
    JOIN category 
    ON  lot.category_id=category.id 
    WHERE lot.id=$item
    "; 
    $db_result_lot = mysqli_query($connection, $sql);
    if(!$db_result_lot){
        $error = mysqli_error($connection);
        print("Ошибка MySQL: ".$error);
    }
    $result_lot=mysqli_fetch_all($db_result_lot, MYSQLI_ASSOC);
    return $result_lot;}



