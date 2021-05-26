<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli('127.0.0.1', 'root', 'rootpass', 'DWWM', 8889);

function execute_query($query, $types, $params)
{
    global $mysqli;
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt;
    // return $stmt->get_result();
}

function query($query)
{
    global $mysqli;
    return $mysqli->query($query);
}

function sanitize_input($input) 
{
    global $mysqli;
    return array_map(function ($str) use ($mysqli){
        return mysqli_real_escape_string($mysqli, $str);
    }, $input);
}

function close_db()
{
    global $mysqli;
    $mysqli->close();
}
