<?php

use App\App;
use App\Domain\Application\Security\TokenCsrf;
use App\Domain\Application\Session\Flash;
use App\Domain\Application\Session\PHPSession;
use App\Domain\Auth\Security\UserChecker;

PHPSession::get();
UserChecker::UserConnect($r->generate('account'));

    $title = "Se connecter";
    $nav = "login";
    $error = false;

    if(!empty($_POST)) {

        if (!TokenCsrf::validateToken($_POST['csrf'])) {
            Flash::flash('danger', 'Token CSRF invalide');
            header('Location: ' . $r->generate('login')); exit;
        }

        $pdo = App::getPDO();
        $auth = App::getAuth();

        $username = strip_tags($_POST['username']);
        $password = strip_tags($_POST['password']);
        $remeber = $_POST['remember'] ?? null;

        try {
            $user = $auth->login($username, $password, $remeber);
        } catch(Exception $e) {
            Flash::flash('danger', $e->getMessage());
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
    <main class="form-signin w-100 m-auto">
        <form action="" method="post">
            <?= TokenCSRF::getFormField() ?>
            <h1 class="display-5 mb-3 fw-normal">Se connecter</h1>
            <div class="form-floating">
                <input type="text" name="username" class="form-control" id="floatingInput" placeholder="name@example.com" autofocus required autocomplete="off" value="<?= isset($_POST['username']) ? htmlentities($_POST['username']) : '' ?>">
                <label class="label" for="floatingInput">NOM D'UTILISATEUR OU EMAIL</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required autocomplete="off">
                <label class="label" for="floatingPassword">MOT DE PASSE</label>
            </div>
            <?php if($error): ?>
                <div class="text-danger text-center p-2 rounded-3 bg-danger-subtle lead mt-3">
                    Identifiant incorrect &#41;&#58;
                </div>
            <?php endif; ?>
            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Se souvenir de moi
                </label>
            </div>
            <div class="mt-3">
                <a class="text-decoration-none text-danger" href="<?= $r->generate('forget') ?>">Mot de passe oublié !</a>
            </div>
            <div class="mt-3">
                <p>
                    <small class="text-body-secondary">Si vous n'avez pas de compte, <a href="<?= $r->generate('sign') ?>" class="fw-bold">Inscrivez vous.</a></small>
                </p>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit">Se connecter</button>
        </form>
    </main>
</div>
