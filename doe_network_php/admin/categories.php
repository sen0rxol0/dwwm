<?php
$documentTitle = "Catégories doe-network";
require_once __DIR__.'/../database/adapter.php';

function validate_name()
{
    global $errors;
    if (isset($_POST['name'])) {
        if (!empty($_POST['name'])) {
            return true;
        }
        $errors['name'] = 'Nom de catégorie est obligatoire.';
    }
    return false;
}

function find_category($param, $value)
{
    $exists = false;
    if ($stmt = execute_query("SELECT * FROM categories WHERE {$param} = ?", array($value))) {
        if ($stmt->rowCount() > 0) {
            $exists = true;
        }
        $stmt->closeCursor();
        unset($stmt);
    }
    return $exists;
}

function create_category()
{
    global $errors;
    if (find_category('name', $_POST['name'])) {
        $errors['name'] = "Cette catégorie <strong>{$_POST['name']}</strong> existe! Veuillez en choisir un autre nom.";
    } else {
        execute_query("INSERT INTO categories (name, created_at) VALUES (?, NOW());", array($_POST['name']));
        $_SESSION['success'] = 'Nouvelle catégorie a été enregistré avec succés.';
    }
}

function update_category()
{
    global $errors;
    $category_id = intval(filter_var($_POST['category_id'], FILTER_SANITIZE_URL));
    if (find_category('name', $_POST['name'])) {
        $errors['name'] = "Cette catégorie <strong>{$_POST['name']}</strong> existe! Veuillez en choisir un autre nom.";
    } else {
        execute_query("UPDATE categories SET name = ? WHERE id = ?", array($_POST['name'], $category_id));
        $_SESSION['success'] = 'Catégorie a été modifiée avec succés.';
    }
    unset($category_id);
}

function delete_category()
{
    $category_id = intval(filter_var($_POST['category_id'], FILTER_SANITIZE_URL));
    if (find_category('id', $category_id)) {
        execute_query("DELETE FROM categories WHERE id = ?", array($category_id));
        $_SESSION['success'] = 'Catégorie a été suprimée avec succés.';
    } else {
        $errors['error'] = 'Catégorie n\'existe pas.';
    }
    unset($category_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array();
    if (!empty($_POST)) {
        if (isset($_POST['_method']) ) {
            switch ($_POST['_method']) {
                case 'PUT':
                    if (validate_name()) {
                        update_category();
                    }
                break;
                case 'DELETE':
                    delete_category();
                break;
            }
        } else {
            if (validate_name()) {
                create_category();
            }
        }
    }
}

if ($stmt = $pdo->query("SELECT * FROM categories;")) {
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($_GET)) {
        if (isset($_GET['editer'])) {
            $edit_category = array_values(array_filter($categories, function($category) {
                return $category['id'] === $_GET['editer'];
            }))[0];
        }
    }
    unset($stmt, $pdo);
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

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="col">
            <div id="categories_crud_accordion_" class="w-50 accordion accordion-flush">
                <?php if(!isset($edit_category)): ?>
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
                        <form action="/admin/categories" method="POST">
                            <fieldset>
                                <legend>Nouvelle cat&eacute;gorie</legend>
                                <div class="my-2 form-group">
                                    <label for="name_">Nom cat&eacute;gorie&colon;</label>
                                    <input id="name_" name="name" type="text" class="form-control">
                                </div>
                                <div class="my-2 form-group d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                                </div>
                            </fieldset>
                        </form>
                        
                    </div>
                </div>
                <?php else: ?>
                <div class="accordion-item">
                    <!-- <button 
                        class="accordion-button collapsed d-none" 
                        id="edit_category__btn_accordion" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#edit_category_">
                        Edit&eacute;r cat&eacute;gorie
                    </button> -->
                    <div id="edit_category_" data-bs-parent="#categories_crud_accordion_" class="accordion-collapse <?= isset($edit_category) ?  '':'collapse'; ?>">
                        <form id="edit_category__form" class="mt-2" action="/admin/categories" method="POST">
                            <fieldset>
                                <legend>Edit&eacute;r cat&eacute;gorie</legend>
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="category_id" value="<?= $edit_category['id'] ?>">
                                <div class="my-2 form-group">
                                    <label for="edit_name_">Nom cat&eacute;gorie&colon;</label>
                                    <input id="edit_name_" name="name" value="<?= $edit_category['name'] ?>" type="text" class="form-control">
                                </div>
                                <div class="my-2 form-group d-flex justify-content-end">
                                    <a class="btn btn-secondary" href="/admin/categories">Annuler</a>
                                    <button class="btn btn-primary" type="submit">Sauvegarder</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php if (isset($categories) && count($categories)): ?>
            <table class="table table-striped table-border border-primary">
                <thead>
                    <tr>
                        <th>Nom cat&eacute;gorie</th>
                        <th>Date de cr&eacute;ation</th>
                        <th>&hellip;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $category): ?>
                        <tr>
                            <td><?= $category['name'] ?></td>
                            <td><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $category['created_at'])->format('d-m-Y'); ?></td>
                            <td class="d-flex" data-category-id="<?= $category['id'] ?>" data-category-name="<?= $category['name'] ?>">
                                <a href="?editer=<?= $category['id'] ?>" class="btn btn-sm btn-outline-secondary">&Eacute;diter</a>
                                <form action="/admin/categories" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="alert alert-primary" role="alert">
                Aucune cat&eacute;gorie enregistr&eacute;!
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php
$page = ob_get_clean();
include __DIR__.'/../layouts/admin.php';
?>