<?php
 $documentTitle = 'Contact';
include dirname(__DIR__).'/../includes/helpers.php';
ob_start();
?>
    <h1>Page de contact</h1>
<?php
$page = ob_get_clean();
include_once dirname(__DIR__)."/../layouts/base.php";