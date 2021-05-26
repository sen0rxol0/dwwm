<?php
session_start();
$documentTitle = 'Inscription';
include_once __DIR__.'/partials/head.php';

function submit_register()
{
    global $register_info;
    global $errors;
    $stmt = execute_query("SELECT * FROM users WHERE email = ?", 's', [$register_info['email']]);
    $email_exists = $stmt->get_result();
    if ($email_exists->num_rows === 0) {
        $stmt->close();
        $register_info['password'] = password_hash($register_info['password'], PASSWORD_BCRYPT);
        $params = [$register_info['name'], $register_info['email'], $register_info['password']];
        execute_query("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())", 'sss', $params);
    } else {
        $errors['email'] = "Cette adresse mail existe déjà. Veuillez essayer la connexion.";
    }
    unset($email_exists, $stmt);
}

function validate_register(): bool
{
    global $errors;
    $email_reg_exp = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/";
    // Validation pourle champ Nom
    if (empty($_POST['name'])) {
        $errors['name'] = 'Nom est obligatoire.';
    } else if (preg_match("/[0-9]/", $_POST['name'])) {
        $errors['name'] = 'Veuillez entrer uniquement des lettres.';
    }
    // Validation pour le champ Email
    if (empty($_POST['email'])) {
        $errors['email'] = 'L\'adresse mail est obligatoire.';
    } else if (!preg_match($email_reg_exp, $_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L\'adresse mail entré n\'est pas valide.';
    }
    // Validation pour le champ Mot de passe
    if (empty($_POST['password'])) {
        $errors['password'] = 'Mot de passe est obligatoire.';
    } else if (mb_strlen($_POST['password']) < 6) {
        $errors['password'] = 'Mot de passe requis au moins 6 caractères.';
    } else if (empty($_POST['password_confirm'])) {
        $errors['password'] = 'Veuillez confirmer le mot de passe.';
    } else if ($_POST['password_confirm'] !== $_POST['password']) {
        $errors['password'] = 'Mots de passe ne correspondent pas.';
    }
    // else if (!preg_match("/[a-zA-Z0-9]/"))

    if (empty($errors)) {
        return true;
    }
    return false;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST)) {
        $errors = array();
        if (validate_register()) {
            require_once __DIR__.'/includes/database.php';
            $register_info = sanitize_input($_POST);
            submit_register();
            if (empty($errors)) {
                close_db();
                // $_SESSION['message'] = 'Merci de votre inscription.';
                $_SESSION['user'] = array(
                    'email' => $register_info['email'],
                    'name' => $register_info['name'],
                    'time' => time()
                );
                unset($errors, $register_info);
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
    <h1>Inscrivez-vous</h1>
    <?php if (isset($message)): echo "<p>$message</p>"; endif; ?>
    <div class="wrap__form">
        <form action="register.php" method="POST">
            <span>
                <label for="name_">Nom &colon;</label>
                <input type="text" name="name" id="name_" value="<?= $register_info['name'] ?? '' ?>">
                <?php if (isset($errors) && isset($errors['name'])): echo "<small>$errors[name]</small>"; endif; ?>
            </span>
            <span>
                <label for="email_">Email &colon;</label>
                <input type="email" name="email" id="email_" required value="<?= $register_info['email'] ?? '' ?>">
                <?php if (isset($errors) && isset($errors['email'])): echo "<small>$errors[email]</small>"; endif; ?>
            </span>
            <span>
                <label for="password_">Mot de passe &colon;</label>
                <input type="password" name="password" id="password_" required>
                <?php if (isset($errors) && isset($errors['password'])): echo "<small>$errors[password]</small>"; endif; ?>
            </span>
            <span>
                <label for="password_confirm_">Confirmation du mot du passe &colon;</label>
                <input type="password" name="password_confirm" id="password_confirm_" required>
            </span>
            <button type="submit" title="S'inscrire">S&apos;inscrire</button>
        </form>
    </div>
</main>
<?php include_once __DIR__.'/partials/foot.php'; ?>
