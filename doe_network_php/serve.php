<?php
// START MYSQL; SERVE PHP;
$platform_windows = false;
$port = 3000;
$public = __DIR__."/public/";
$command_php = "php -S localhost:$port -t $public";
$command_mysql = '/Applications/MAMP/Library/bin/mysqld_safe  --defaults-file=/Applications/MAMP/tmp/mysql/my.cnf --port=8889 --socket=/Applications/MAMP/tmp/mysql/mysql.sock --pid-file=/Applications/MAMP/tmp/mysql/mysql.pid --log-error=/Applications/MAMP/logs/mysql_error.log --tmpdir=/Applications/MAMP/tmp/mysql/tmpdir --datadir=/Library/Application\ Support/appsolute/MAMP\ PRO/db/mysql57';
$command_mysql_stop = '/Applications/MAMP/Library/bin/mysqladmin -u root -prootpass --socket=/Applications/MAMP/tmp/mysql/mysql.sock shutdown';

if (preg_match('/Windows/', php_uname())) {
    $platform_windows = true;
    // $command_mysql = "";
}

function start_serve(): void
{
    global $command_mysql;
    global $command_php;
    shell_exec("$command_mysql > /dev/null &"); // Ex&eacute;cution de la commande pour démarrer le serveur mysql
    shell_exec("$command_php > /dev/null & echo $! > /tmp/serve_php.pid"); // Ex&eacute;cution de la commande serveur interne php
    exit;
}

function stop_serve(): void
{
    global $command_mysql_stop;
    shell_exec($command_mysql_stop); // Ex&eacute;cution de la commande pour arrêter le serveur mysql
    $php_serve_pid = trim(file_get_contents("/tmp/serve_php.pid"));
    shell_exec("kill $php_serve_pid"); // Ex&eacute;cution de la commande de suppression du processus utilisé pour lancer le serveur interne php
    exit;
}

function exec_cross_platform($cmd): void
{
    global $platform_windows;
    if ($platform_windows) {
        pclose(popen("start /B $cmd", "r"));
    } else {
        shell_exec("$cmd > /dev/null &");
    }
}

if (isset($argv[1])) {
    if ($argv[1] == '-d' || $argv[1] == '-s') {
        start_serve();
    } else if ($argv[1] == '-a' || $argv[1] == '-ss') {
        stop_serve();
    }
} else {
    echo 'Utiliser `-d` ou `-s` pour démarrer le serveur.'."\n";
    echo 'Utiliser `-a` ou `-ss` pour arrêter le serveur.'."\n\n";
    echo 'Exemple: `php serve.php -d`';
    exit;
}
?>