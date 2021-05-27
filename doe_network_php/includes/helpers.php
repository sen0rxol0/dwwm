<?php
session_start();
function validate_auth() {
    if (isset($_SESSION['user'])) {
        return true;
    }
    return false;
}

function auth() {
    if (!isset($_SESSION['user'])) {
        header("Location: /login");
        exit;
    }
}
