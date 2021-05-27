<?php
$documentTitle = "Catégories doe-network";
require_once __DIR__.'/../database/adapter.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array();
    if (!empty($_POST)) {
        if (isset($_POST['name'])) {
            if (!empty($_POST['name'])) {
                if ($stmt = execute_query("SELECT * FROM categories WHERE name = ?", array($_POST['name']))) {
                    if ($stmt->rowCount() > 0) {
                        $errors['name'] = 'Nom existant! Veuillez choisir un autre nom.';
                    } else {
                        execute_query("INSERT INTO categories (name, created_at) VALUES (?, NOW());", array($_POST['name']));;
                    }
                    unset($stmt);
                }
            } else {
                $errors['name'] = 'Nom est obligatoire.';
            }
        }
    }
}
if ($stmt = $pdo->query("SELECT * FROM categories;")) {
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    unset($stmt);
}
ob_start();
?>
    <h1>Bienvenue <?= $_SESSION['user']['name'] ?></h1>
    <h2>Liste des cat&eacute;gories</h2>

    <div class="row">
        <?php if (isset($errors) && count($errors)):
            foreach($errors as $error):  ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
        <?php endforeach; endif; ?>
        <div class="col">
            <div id="categories_crud_accordion_" class="w-50 accordion accordion-flush">
                <div class="accordion-item">
                    <button 
                        class="accordion-button collapsed" 
                        type="button" 
                        id="new_category__btn"
                        data-bs-toggle="collapse"
                        data-bs-target="#add_new_">
                            Ajouter nouvelle&hellip;
                    </button>
                    <div id="add_new_" data-bs-parent="#categories_crud_accordion_" class="accordion-collapse collapse">
                        <form action="" method="POST">
                            <div class="my-2 form-group">
                                <label for="name_">Nom cat&eacute;gorie&colon;</label>
                                <input id="name_" name="name" type="text" class="form-control">
                            </div>
                            <div class="my-2 form-group d-flex justify-content-end">
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php if (isset($categories) && count($categories)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom cat&eacute;gorie</th>
                        <th>Cr&eacute;e</th>
                        <th>&hellip;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $category): ?>
                        <tr>
                            <td><?= $category['name'] ?></td>
                            <td><?= $category['created_at'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary">&Eacute;diter</button>
                                <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
<?php
$page = ob_get_clean();
include __DIR__.'/../layouts/admin.php';
?>