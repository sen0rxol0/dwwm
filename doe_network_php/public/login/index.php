<?php
 $documentTitle = 'Connexion';
include dirname(__DIR__).'/../includes/helpers.php';
if (validate_auth()) {
    unset($_SESSION['user']);
    session_destroy();
    header("Location: /");
}

function validate_login(): bool
{
    global $errors;
    global $login_info;
    if (empty($_POST['email'])) {
        $errors['email'] = 'L\'adresse mail est obligatoire.';
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L\'adresse mail estré n\'est pas valide.';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Mot de passe est obligatoire.';
    }
    if (empty($errors)) {
        $login_info['email'] = htmlspecialchars($_POST['email']);
        $login_info['password'] = htmlspecialchars($_POST['password']);
        return true;
    }
    return false;
}

function submit_login()
{
    global $errors;
    global $login_info;
    $stmt = execute_query("SELECT * FROM users WHERE email = ?", [$login_info['email']]);
    if ($stmt->rowCount() !== 0) {
        $user = (object)$stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if (!password_verify($login_info['password'], $user->password)) {
            $errors['password'] = 'Mot de passe ou adresse mail incorrects.';
        } else {
            $login_info['name'] = "$user->first_name $user->last_name";
        }
    } else {
        $errors['password'] = 'Mot de passe ou adresse mail incorrects.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST)) {
        $errors = array();
        $login_info = array();
        if (validate_login()) {
            require_once dirname(__DIR__).'/../database/adapter.php';
            submit_login();
            if (empty($errors)) {
                // unset($pdo);
                // $_SESSION['message'] = 'Merci de votre inscription.';
                $_SESSION['user'] = array(
                    'email' => $login_info['email'],
                    'name' => $login_info['name'],
                    'time' => time()
                );
                unset($errors, $login_info);
                header("Location: /admin");
                exit;
            }
        } else {
            $message = 'Veuillez remplir le formulaire.';
        }
    }
}

 ob_start();
?>
    <h1 class="my-3 text-center card-header">Page de connexion</h1>
    <div class="row">
        <div class="mt-4 col-12">
            <form class="w-50 mx-auto" action="/login" method="POST">
                <div class="my-2 form-group">
                    <label for="email_">Email&colon;</label>
                    <input type="email" class="form-control" id="email_" name="email">
                    <small><?= $errors['email'] ?? ''?></small>
                </div>
                <div class="my-2 form-group">
                    <label for="password_">Mot de passe&colon;</label>
                    <input type="password" class="form-control" id="password_" name="password">
                    <small><?= $errors['password'] ?? ''?></small>
                </div>
                <div class="my-2 form-group d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit" title="Se connecter">Connecter</button>
                </div>
            </form>
        </div>
    </div>
<?php
$page = ob_get_clean();
include_once dirname(__DIR__)."/../layouts/base.php";