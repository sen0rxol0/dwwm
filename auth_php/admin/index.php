<?php
session_start();
if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit();
}

$documentTitle = "Admin";
include_once __DIR__.'/../partials/head.php';







