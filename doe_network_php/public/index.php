<?php
// $ php -i | grep pdo_mysql // Liste les extensions d'API PHP et recherche pour PDO

/**
 * 
 * Besoins:
 *  - responsive
 *  - base de données: Categorie 1 N Expertise, Expertise 1 1 Categorie, users
 *  - pages: accueil,expertises,contact
 *  - backoffice avec authentication
 *  - fonc_backoffice: 
 *      - crud catégories
 *      - crud expertises
 *      - voir/lire messages du formulaire contact
 *  - stack: Bootstrap 5 AMP
 *  - logo
 *  - ff: Poppins
 * 
 */
$documentTitle = 'Accueil';
include __DIR__.'/../includes/helpers.php';
ob_start();
?>
    <h1>Page d'accueil</h1>
<?php
$page = ob_get_clean();
include_once __DIR__."/../layouts/base.php";