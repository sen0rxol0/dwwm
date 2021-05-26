<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <?php if (isset($_SESSION) && isset($_SESSION['user'])): ?>
        <li><a href="login.php">Deconnexion</a></li>
        <?php else: ?>
        <li><a href="login.php">Connexion</a></li>
        <li><a href="register.php">Inscription</a></li>
        <?php endif; ?>
    </ul>
</nav>