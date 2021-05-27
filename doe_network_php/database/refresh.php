<?php
require_once 'adapter.php';

create_categories_table();
create_expertises_table();
drop_users_table();
create_users_table();
insert_admin_user();
unset($pdo);

function create_expertises_table()
{   
    query_from_file('expertises');
}

function create_categories_table()
{   
    query_from_file('categories');
}

function drop_users_table()
{
    global $pdo;
    $pdo->query("DROP TABLE IF EXISTS users");
        // $pdo->query("TRUNCATE TABLE users");
}

function create_users_table()
{
    query_from_file('users');
}

function insert_admin_user()
{
    global $pdo;
    $insert_admin_query = $pdo->prepare("INSERT INTO 
        users (first_name, last_name, email, password, created_at) 
        VALUES ('Walter', 'Varela', 'varela@baieflow.com', :password, NOW())
    ");
    // $insert_admin_query->bindValue(':password', password_hash('varela@baieflow.com', PASSWORD_BCRYPT), PDO::PARAM_STR);
    $insert_admin_query->execute([':password' => password_hash('varela@baieflow.com', PASSWORD_BCRYPT)]);
    print "\nNouveau utilisateur admin inseré.";
    unset($insert_admin_query);
}

function query_from_file($filename)
{
    global $pdo;
    $query = file_get_contents(__DIR__."/$filename.sql");
    $pdo->query($query);
    print "Requête executé: $query \n";
    unset($query);
}
