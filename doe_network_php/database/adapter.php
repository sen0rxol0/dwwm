<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=dwwm_doe_network;port=8889', 'root', 'rootpass');
} catch(PDOException $exc) {
    print "Error: ".$exc->getMessage();
    exit;
}

function execute_query($query, $values)
{
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute($values);
    return $stmt;
}