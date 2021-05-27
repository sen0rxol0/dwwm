<?php include_once __DIR__.'/head.php'; ?>
<body>
    <div class="container">
        <nav>
            <ul class="nav justify-content-end">
                <?php if (validate_auth()): ?>
                    <li class="nav-item">
                        <a class="nav-link bg-primary text-white" href="/admin">Espace administration</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/expertises">Mes expertises</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
                <?php if (!validate_auth()): ?>
                    <li style="font-weight: 200;" class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <main class="">
            <?= $page ?>
        </main>
    </div>
</body>
</html>