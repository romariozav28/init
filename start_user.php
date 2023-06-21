<?php

session_start();

$is_auth = isset($_SESSION['user_name']);
    $user_name = '';
    if ($is_auth) {$user_name = $_SESSION['user_name'];
    }
