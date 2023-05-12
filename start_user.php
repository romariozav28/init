<?php

session_start();

$is_auth = isset($_SESSION['user_name']);

    $user_name = $_SESSION['user_name'];

