<?php include_once __DIR__.'/head.php'; ?>
<body>
    <div class="container">
        <nav>
            <ul class="nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/expertises">Expertises</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/categories">Catégories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/login">Déconnexion</a>
                </li>
            </ul>
        </nav>

        <main class="content__admin">
            <?= $page ?>
        </main>
    </div>
</body>
</html>