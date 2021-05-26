<?php
session_start();
if (isset($_SESSION) && isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    session_destroy();
    header("Location: /index.php");
}

$documentTitle = 'Connexion';
include_once __DIR__.'/partials/head.php';

function validate_login(): bool
{
    global $errors;
    if (empty($_POST['email'])) {
        $errors['email'] = 'L\'adresse mail est obligatoire.';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Mot de passe est obligatoire.';
    }
    if (empty($errors)) {
        return true;
    }
    return false;
}

function submit_login()
{
    global $errors;
    global $login_info;
    $stmt = execute_query("SELECT * FROM users WHERE email = ?", 's', [$login_info['email']]);
    $res = $stmt->get_result();
    if ($res->num_rows !== 0) {
        $user = (object)$res->fetch_assoc();
        $stmt->close();
        if (!password_verify($login_info['password'], $user->password)) {
            $errors['password'] = 'Mot de passe ou adresse mail incorrects.';
        } else {
            $login_info['name'] = $user->name;
        }
    } else {
        $errors['password'] = 'Mot de passe ou adresse mail incorrects.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST)) {
        $errors = array();
        if (validate_login()) {
            require_once __DIR__.'/includes/database.php';
            $login_info = sanitize_input($_POST);
            submit_login();
            if (empty($errors)) {
                close_db();
                // $_SESSION['message'] = 'Merci de votre inscription.';
                $_SESSION['user'] = array(
                    'email' => $login_info['email'],
                    'name' => $login_info['name'],
                    'time' => time()
                );
                unset($errors, $login_info);
                header("Location: admin/index.php");
                // die();
            }
        } else {
            $message = 'Veuillez remplir le formulaire.';
        }
    }
}
?>
<main>
    <h1>Connectez-vous</h1>
    <?php if (isset($message)): echo "<p>$message</p>"; endif; ?>
    <div class="wrap__form">
        <form action="login.php" method="POST">
            <span>
                <label for="email_">Email &colon;</label>
                <input type="email" name="email" id="email_" required value="<?= $login_info['email'] ?? '' ?>">
                <?php if (isset($errors) && isset($errors['email'])): echo "<small>$errors[email]</small>"; endif; ?>
            </span>
            <span>
                <label for="password_">Mot de passe &colon;</label>
                <input type="password" name="password" id="password_" required>
                <?php if (isset($errors) && isset($errors['password'])): echo "<small>$errors[password]</small>"; endif; ?>
            </span>
            <button type="submit" title="Se connecter">Se connecter</button>
        </form>
    </div>
</main>

<?php include_once __DIR__.'/partials/foot.php'; ?>
