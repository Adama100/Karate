<?php

use App\App;
use App\Domain\Security\UserChecker;
use App\Domain\Security\TokenCSRF;
use App\Session;

Session::getSession();
UserChecker::UserConnect($r->generate('account'));

    $title = "Se connecter";
    $nav = "login";
    $pdo = App::getPDO();
    $auth = App::getAuth();
    $error = false;

    if(!empty($_POST)) {

        if (!TokenCSRF::validateToken($_POST['csrf'])) {
            Session::flash('danger', 'Token CSRF invalide');
            header('Location: ' . $r->generate('login')); exit;
        }
        $username = strip_tags($_POST['username']);
        $password = strip_tags($_POST['password']);
        $remeber = $_POST['remember'] ?? null;

        try {
            $user = $auth->login($username, $password, $remeber);
        } catch(Exception $e) {
            Session::flash('danger', $e->getMessage());
            header('Location: ' . $r->generate('login')); exit;
        }
        if($user) {
            header('Location: ' . $r->generate('account')); exit; 
        } else {
            password_hash($password, PASSWORD_ARGON2I);
            $error = true;
        }
    }

?>

<div class="container">
    <h4 class="mb-3 text-primary">Se Connecter</h4>
    <form action="" method="post">
        <?= TokenCSRF::getFormField() ?>
        <div class="form-group">
            <div class="form-label">
                <label for="username">NOM D'UTILISATEUR OU EMAIL</label>
                <input type="text" name="username" autofocus id="username" class="form-control" required autocomplete="off" value="<?= isset($_POST['username']) ? htmlentities($_POST['username']) : '' ?>">
            </div>
            <div class="form-label">
                <label for="password">MOT DE PASSE</label>
                <input type="password" name="password" id="password" class="form-control" required autocomplete="off">
            </div>
            <?php if($error): ?> 
                <div class="text-danger text-center p-2 rounded-3 bg-danger-subtle lead mt-3">
                    Nom d'utilisateur ou mot de passe incorrect &#41;&#58;
                </div>
            <?php endif; ?> 
            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" name="remember" value="remember-me" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Se souvenir de moi
                </label>
            </div>
            <div class="mt-3">
                <a class="text-decoration-none text-danger" href="<?= $r->generate('forget') ?>">Mot de passe oublié !</a>
            </div>
            <div class="mt-3">
                <small class="text-body-secondary">Si vous n'avez pas de compte, <a href="<?= $r->generate('sign') ?>" class="fw-bold">Inscrivez vous.</a></small>
                <p class="mt-2 text-body-secondary">&copy; 2023–2024</p>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </div>
    </form>
</div>