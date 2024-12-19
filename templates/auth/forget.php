<?php

use App\Session;
use App\App;
use App\Domain\Auth\User;
use App\Helper\MailHelper;

Session::getSession();
header('Turbolinks-Location: ' . $r->generate('forget'));

    if (isset($_POST['email']) && !empty($_POST['email'])) {

        $pdo = App::getPDO();
        $email = strtolower(strip_tags($_POST['email']));
        $check = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $check->execute([$email]);
        $check->setFetchMode(PDO::FETCH_CLASS, User::class);
        $user = $check->fetch();

        if($user) {

            $token = bin2hex(openssl_random_pseudo_bytes(64));
            $pdo
                ->prepare('UPDATE users SET reset_password_token = ?, reset_password_at = NOW() WHERE id = ?')
                ->execute([$token, $user->getId()]);

                $link = 'http://localhost' . $r->generate('reset') . '?id=' . $user->getId() . '&sign_token=' . $token;
                echo "<a href='$link'>Lien</a>";
                if (!defined('RESET_EMAIL')) {
                    define('RESET_EMAIL', [
                        'link' => $link,
                        'pseudo' => $user->getUsername()
                    ]);
                }
                $sujet = "Reinitialisation de votre mot de passe";
                $messages = MailHelper::getPage('../templates/mails/auth/password_reset.php');

                try {
                    $mail = new MailHelper($email, $sujet, $messages);
                    $mail->token();
                    Session::flash('success', "Les instructions du rappel du mot de passe vous ont été envoyé par email");
                    header('Location: '. $r->generate('login')); exit;
                } catch (Exception) { Session::flash('danger', "Message non envoyé: Erreur connexion"); }
        } else { Session::flash('danger', "Aucun compte ne correspond à cette adresse"); }
    }

?>

<div class="container mt-5 py-4">
    <div class="m-auto w-50">
        <div class="card text-center m-2 p-3 mb-5 rounded-1 border border-none">
            <div class="card-body">
                <h4 class="text-center text-danger mb-4">Mot de passe oublié !</h4>
                <div class="form-group">
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control shadow-sm rounded-1" name="email" placeholder="Votre email" value="<?php if(isset($_POST['email'])): ?><?= htmlentities($_POST['email']) ?><?php endif; ?>" autofocus autocomplete="off" required>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary mt-3">M'envoyer les instructions</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
