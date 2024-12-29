<?php

use App\App;
use App\Domain\Application\Security\TokenCsrf;
use App\Domain\Application\Session\Flash;
use App\Domain\Application\Session\PHPSession;
use App\Domain\Application\Validator\BootstrapValidator;
use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Security\UserChecker;
use App\Domain\Auth\UserValidator;
use App\Helper\MailHelper;

PHPSession::get();
UserChecker::UserConnect($r->generate('index'));

    $title = "S'inscrire";
    $nav = "sign";
    $pdo = App::getPDO();
    $errors = [];

    if(!empty($_POST)) {

        if (!TokenCsrf::validateToken($_POST['csrf'])) {
            Flash::flash('danger', 'Token CSRF invalide');
        }

        $user = new User();
        $userTable = new UserRepository($pdo);

        $pseudo = strip_tags($_POST['username']);
        $email = strtolower(strip_tags($_POST['email']));
        $number = strip_tags($_POST['number']);
        $password = strip_tags($_POST['password']);
        $repass = strip_tags($_POST['conpass']);

        $messages = new UserValidator($pdo);
        $message = $messages->signValidator($pseudo, $email, $password, $repass);
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
            $id = $user->getId(); # lastInsertId

            $link = 'http://localhost' . $r->generate('sign.check') . '?id='.$id.'&sign_token='.$token;
            define('SIGN_MAIL', $link);
            echo "<a href='$link'>Lien</a>";

            $sujet = "Confirmation du compte";
            $messages = MailHelper::getPage('../templates/mails/auth/register.php');

            try {
                $mail = new MailHelper($email, $sujet, $messages);
                $mail->token();
                Flash::flash('success', "un email de confirmation de votre compte vous a été envoyé");
                header('Location: ' . $r->generate('login')); exit;
            } catch (Exception) { Flash::flash('danger', "Message non envoyé: Erreur connexion"); }
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
                        <input type="text" name="username" id="username" class="form-control <?= BootstrapValidator::isValid($errors, 'username') ?>" required autocomplete="off" value="<?= isset($_POST['username']) ? htmlentities($_POST['username']) : '' ?>">
                        <?= BootstrapValidator::inValidFeedback($errors, 'username') ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-label">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?= BootstrapValidator::isValid($errors, 'email') ?>" required autocomplete="off" value="<?= isset($_POST['email']) ? htmlentities($_POST['email']) : '' ?>">
                        <?= BootstrapValidator::inValidFeedback($errors, 'email') ?> 
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-label">
                        <label for="number">Numéro de téléphone</label>
                        <input type="number" name="number" id="number" class="form-control <?= BootstrapValidator::isValid($errors, 'number') ?>" required autocomplete="off" value="<?= isset($_POST['number']) ? htmlentities($_POST['number']) : '' ?>">
                        <?= BootstrapValidator::inValidFeedback($errors, 'number') ?> 
                    </div>
                </div>
            </div>
            <div class="form-label row">
                <div class="col-md form-label">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control <?= BootstrapValidator::isValid($errors, 'password') ?>" required autocomplete="off">
                    <?= BootstrapValidator::inValidFeedback($errors, 'password') ?>
                </div>
                <div class="col-md form-label">
                    <label for="conpass">Confirmer le mot de passe</label>
                    <input type="password" name="conpass" id="conpass" class="form-control <?= BootstrapValidator::isValid($errors, 'password') ?>" required autocomplete="off">
                </div>
            </div>
            <button type="submit" class="btn btn-warning">S'inscrire</button>
            <hr class="my-4">
            <small class="text-body-secondary">Inscrivez vous en toute securité.</small>
        </div>
    </form>
</div>
