<?php

require_once ('init.php');


$sql_input_lot='INSERT INTO lot (lot_name, lot_description, lot_price_start, lot_price_step, lot_date_end, category_id, lot_image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?);';
$stmt = mysqli_prepare($link, $sql_input_lot);

        $a1 = "wwwwwwwwwwwww";
        $a2 = "eeeeeeeeeeeeeeeeeeeee";
        $a3 = "500";
        $a4 = "100";
        $a5 = "2023-02-10";
        $a6 = "1";
        $a7 = "uploads/dddddd";
        $a8 = "1";
        


        mysqli_stmt_bind_param($stmt, "ssssssss", $a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8);

        $res=mysqli_stmt_execute($stmt);