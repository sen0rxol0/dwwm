<?php
$documentTitle = 'Expertises';
include dirname(__DIR__).'/../includes/helpers.php';
ob_start();
?>
    <h1>Page d'expertises</h1>
<?php
$page = ob_get_clean();
include_once dirname(__DIR__)."/../layouts/base.php";