<?php
$documentTitle = "Expertises doe-network";
require_once __DIR__.'/../database/adapter.php';

function validate_input()
{
    global $errors;
    if (!isset($_POST['category']) || empty($_POST['category'])) {
        $errors['category'] = 'Catégorie est obligatoire.';
    }
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        $errors['name'] = 'Nom d\'expertise est obligatoire.';
    }
    if (!isset($_POST['description']) || empty($_POST['description'])) {
        $errors['description'] = 'Description est obligatoire.';
    }
    // if (!isset($_POST['image']))
    if (empty($errors)) {
        return true;
    }
    return false;
}

function find_expertise($param, $value)
{
    $exists = false;
    if ($stmt = execute_query("SELECT * FROM expertises WHERE {$param} = ?", array($value))) {
        if ($stmt->rowCount() > 0) {
            $exists = true;
        }
        $stmt->closeCursor();
        unset($stmt);
    }
    return $exists;
}

function create_expertise()
{
    global $errors;
    if (find_expertise('name', $_POST['name'])) {
        $errors['name'] = "Cette expertise <strong>{$_POST['name']}</strong> existe! Veuillez en choisir un autre nom.";
    } else {
        execute_query("INSERT INTO categories (name, created_at) VALUES (?, NOW());", array($_POST['name']));
        $_SESSION['success'] = 'Nouvelle expertise a été enregistré avec succés.';
    }
}

function update_expertise()
{
    global $errors;
    $expertise_id = intval(filter_var($_POST['expertise_id'], FILTER_SANITIZE_URL));
    if (find_expertise('name', $_POST['name'])) {
        $errors['name'] = "Cette expertise <strong>{$_POST['name']}</strong> existe! Veuillez en choisir un autre nom.";
    } else {
        if ($stmt = execute_query("UPDATE categories SET name = ? WHERE id = ?", array($_POST['name'], $expertise_id))) {
            $stmt->closeCursor();
            unset($stmt);
            $_SESSION['success'] = 'Expertise a été modifiée avec succés.';
        }
    }
}

function delete_expertise()
{
    $expertise_id = intval(filter_var($_POST['expertise_id'], FILTER_SANITIZE_URL));
    if (find_expertise('id', $expertise_id)) {
        execute_query("DELETE FROM expertises WHERE id = ?", array($expertise_id));
        $_SESSION['success'] = 'Expertise a été suprimée avec succés.';
    } else {
        $errors['error'] = 'Expertise n\'existe pas.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array();
    if (!empty($_POST)) {
        if (isset($_POST['_method']) ) {
            switch ($_POST['_method']) {
                case 'PUT':
                    if (validate_input()) {
                        update_expertise();
                    }
                break;
                case 'DELETE':
                    delete_expertise();
                break;
            }
        } else {
            if (validate_input()) {
                create_expertise();
            }
        }
    }
}

if ($stmt = $pdo->query("SELECT * FROM expertises;")) {
    $expertises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($_GET)) {
        if (isset($_GET['editer'])) {
            $edit_expertise = array_values(array_filter($expertises, function($expertise) {
                return $expertise['id'] === $_GET['editer'];
            }))[0];
        }
    }
    unset($stmt);
}
if ($stmt = $pdo->query("SELECT * FROM categories;")) {
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    unset($stmt, $pdo);
}
ob_start();
?>
    <h1>Bienvenue <?= $_SESSION['user']['name'] ?></h1>
    <h2>Liste des expertises</h2>

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
            <div id="expertises_crud_accordion_" class="w-50 accordion accordion-flush">
                <?php if(!isset($edit_expertise)): ?>
                <div class="accordion-item">
                    <button 
                        class="accordion-button collapsed" 
                        type="button" 
                        id="new_expertise__btn"
                        data-bs-toggle="collapse"
                        data-bs-target="#add_new_">
                            Ajouter nouvelle&hellip;
                    </button>
                    <div id="add_new_" data-bs-parent="#expertises_crud_accordion_" class="accordion-collapse collapse">
                        <?php if (isset($categories) && !count($categories)): ?>
                            <div class="alert alert-warning">
                                Aucune cat&eacute;gorie trouv&eacute;e!
                                <a class="alert-link" href="/admin/categories">Veuillez en cr&eacute;er une nouvelle</a>
                            </div>
                        <?php endif; ?>
                        <form action="/admin/expertises" method="POST">
                            <fieldset>
                                <legend>Nouvelle expertise</legend>
                                <div class="my-2 form-group">
                                    <label for="category_">Cat&eacute;gorie&colon;</label>
                                    <select id="category_" name="category" class="form-select">
                                        <option value="" selected>Veuillez choisir une cat&eacute;gorie</option>
                                        <?php if(isset($categories)): foreach($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <div class="my-2 form-group">
                                    <label for="name_">Nom&colon;</label>
                                    <input id="name_" name="name" type="text" class="form-control">
                                </div>
                                <div class="my-2 form-group">
                                    <label for="description_">Description&colon;</label>
                                    <textarea id="description_" name="description" class="form-control"></textarea>
                                </div>
                                <div class="my-2 form-group">
                                    <label for="image_">Image&colon;</label>
                                    <input id="image_" name="image" type="file" class="form-control">
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
                    <div id="edit_expertise_" data-bs-parent="#expertises_crud_accordion_" class="accordion-collapse <?= isset($edit_expertise) ?  '':'collapse'; ?>">
                        <form id="edit_expertise__form" class="mt-2" action="/admin/expertises" method="POST">
                            <fieldset>
                                <legend>Edit&eacute;r expertise</legend>
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="expertise_id" value="<?= $edit_expertise['id'] ?>">
                                <div class="my-2 form-group">
                                    <label for="edit_name_">Nom expertise&colon;</label>
                                    <input id="edit_name_" name="name" value="<?= $edit_expertise['name'] ?>" type="text" class="form-control">
                                </div>
                                <div class="my-2 form-group d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit">Sauvegarder</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php if (isset($expertises) && count($expertises)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Cat&eacute;gorie</th>
                        <th>Nom expertise</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Data de cr&eacute;ation</th>
                        <th>&hellip;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($expertises as $expertise): ?>
                        <tr>
                            <td><?= $expertise['name'] ?></td>
                            <td><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $category['created_at'])->format('d-m-Y'); ?></td>
                            <td data-expertise-id="<?= $expertise['id'] ?>" data-expertise-name="<?= $expertise['name'] ?>">
                                <a href="?editer=<?= $expertise['id'] ?>" class="btn btn-sm btn-outline-secondary">&Eacute;diter</a>
                                <form action="/admin/expertises" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="expertise_id" value="<?= $expertise['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="alert alert-primary" role="alert">
                Aucune expertise enregistr&eacute;!
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php
$page = ob_get_clean();
include __DIR__.'/../layouts/admin.php';
?>