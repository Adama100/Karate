<?php

use App\App;
use App\Domain\Auth\User;
use App\Domain\Auth\UserTable;
use App\Domain\Auth\UserValidator;
use App\Domain\Security\BootstrapValid;
use App\Domain\Security\TokenCSRF;
use App\Domain\Security\UserChecker;
use App\Helper\MailHelper;
use App\Session;

Session::getSession();
UserChecker::UserConnect($r->generate('index'));

    $title = "S'inscrire";
    $nav = "sign";
    $pdo = App::getPDO();

    $user = new User();
    $userTable = new UserTable($pdo);
    $errors = [];

    if(!empty($_POST)) {

        if (!TokenCSRF::validateToken($_POST['csrf'])) {
            Session::flash('danger', 'Token CSRF invalide');
        }
        $pseudo = strip_tags($_POST['username']);
        $email = strtolower(strip_tags($_POST['email']));
        $number = strip_tags($_POST['number']);
        $password = strip_tags($_POST['password']);
        $repass = strip_tags($_POST['conpass']);

        $messages = new UserValidator($pdo);
        $message = $messages->signValid($pseudo, $email, $password, $repass);
        if(empty($message)) {

            $password = password_hash($password, PASSWORD_ARGON2ID);
            $token = bin2hex(openssl_random_pseudo_bytes(64)); 
            $user
                ->setUsername($pseudo)
                ->setEmail($email)
                ->setPassword($password)
                ->setPhoneNumber($number)
                ->setToken($token);
            $userTable->create($user);
            $id = $user->getId(); // lastInsertId

            $link = 'http://localhost' . $r->generate('sign.check') . '?id='.$id.'&sign_token='.$token;
            define('SIGN_MAIL', $link);
            echo "<a href='$link'>Lien</a>";

            $sujet = "Confirmation du compte";
            $messages = MailHelper::getPage('../templates/mails/auth/register.php');

            try {
                $mail = new MailHelper($email, $sujet, $messages);
                $mail->token();
                Session::flash('success', "un email de confirmation de votre compte vous a été envoyé");
                header('Location: ' . $r->generate('login')); exit;
            } catch (Exception) { Session::flash('danger', "Message non envoyé: Erreur connexion"); }
        } else {
            $errors = $message;
        }
    }

?>

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        Il y'a eu une erreur lors de l'inscription   
    </div> 
<?php endif ?> 

<div class="container">
    <h4 class="mb-3 text-success">S'inscrire</h4>
    <form action="" method="post" id="js-button-disabled-form">
        <?= TokenCSRF::getFormField() ?>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-label">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" name="username" id="username" class="form-control <?= BootstrapValid::isValid($errors, 'username') ?>" required autocomplete="off" value="<?= isset($_POST['username']) ? htmlentities($_POST['username']) : '' ?>">
                        <?= BootstrapValid::inValidFeedback($errors, 'username') ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-label">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?= BootstrapValid::isValid($errors, 'email') ?>" required autocomplete="off" value="<?= isset($_POST['email']) ? htmlentities($_POST['email']) : '' ?>">
                        <?= BootstrapValid::inValidFeedback($errors, 'email') ?> 
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-label">
                        <label for="number">Numéro de téléphone</label>
                        <input type="number" name="number" id="number" class="form-control <?= BootstrapValid::isValid($errors, 'number') ?>" required autocomplete="off" value="<?= isset($_POST['number']) ? htmlentities($_POST['number']) : '' ?>">
                        <?= BootstrapValid::inValidFeedback($errors, 'number') ?> 
                    </div>
                </div>
            </div>
            <div class="form-label row">
                <div class="col-md form-label">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control <?= BootstrapValid::isValid($errors, 'password') ?>" required autocomplete="off">
                    <?= BootstrapValid::inValidFeedback($errors, 'password') ?>
                </div>
                <div class="col-md form-label">
                    <label for="conpass">Confirmer le mot de passe</label>
                    <input type="password" name="conpass" id="conpass" class="form-control <?= BootstrapValid::isValid($errors, 'password') ?>" required autocomplete="off">
                </div>
            </div>
            <button type="submit" class="btn btn-warning">S'inscrire</button>
            <hr class="my-4">
            <small class="text-body-secondary">Inscrivez vous en toute securité.</small>
        </div>
    </form>
</div>
