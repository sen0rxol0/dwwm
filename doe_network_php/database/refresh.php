<?php
require_once 'adapter.php';

drop_table_expertises();
drop_table_categories();
create_table_categories();
create_table_expertises();
drop_table_users();
create_table_users();
insert_user_admin();
unset($pdo);

function drop_table_expertises()
{
    global $pdo;
    $pdo->query("DROP TABLE IF EXISTS expertises");
}

function create_table_expertises()
{   
    query_from_file('expertises');
}

function drop_table_categories()
{
    global $pdo;
    $pdo->query("DROP TABLE IF EXISTS categories");
}

function create_table_categories()
{   
    query_from_file('categories');
}

function drop_table_users()
{
    global $pdo;
    $pdo->query("DROP TABLE IF EXISTS users");
        // $pdo->query("TRUNCATE TABLE users");
}

function create_table_users()
{
    query_from_file('users');
}

function insert_user_admin()
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
