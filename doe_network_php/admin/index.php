<?php
$documentTitle = "Admin doe-network";
ob_start();
?>
    <h1>Bienvenue <?= $_SESSION['user']['name'] ?></h1>
<?php
$page = ob_get_clean();
include __DIR__.'/../layouts/admin.php';
?>