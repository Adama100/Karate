<?php

use App\App;
use App\Session;

Session::getSession();
    $pdo = App::getPDO();
    $id = (int)$_GET['id'];
    $token = htmlspecialchars($_GET['sign_token']);

    if(isset($id) && isset($token) && !empty($id) && !empty($token)) {

        $req = $pdo->prepare('SELECT * FROM users WHERE id = :id AND reset_password_token IS NOT NULL AND reset_password_token = :token AND reset_password_at > DATE_SUB(NOW(), INTERVAL 20 MINUTE)'); 
        $req->execute([
            'id' => $id,
            'token' => $token
        ]);
        $user = $req->fetch();

        if($user) {
            if (!empty($_POST)) {
                if (!empty($_POST['password']) && !empty($_POST['password_repeat'])) {

                    $password = htmlspecialchars($_POST['password']);
                    $password_repeat = htmlspecialchars($_POST['password_repeat']);

                    if($password === $password_repeat) {
                        if(strlen($password) > 4) {
                            $password = password_hash($password, PASSWORD_ARGON2ID);
                            $udapte = $pdo->prepare('UPDATE users SET password = ?, token = null, sign_at = NOW(), reset_password_token = NULL, reset_password_at = NULL WHERE id = ?');
                            $udapte->execute([$password, $user['id']]);

                            // Débloquer le compte
                            $unlock = $pdo->prepare("UPDATE users SET locked_until = NULL WHERE id = ?");
                            $unlock->execute([$user['id']]);
                            $deleteLoginAttempts = $pdo->prepare('DELETE FROM login_attempts WHERE user_id = ?');
                            $deleteLoginAttempts->execute([$user['id']]);

                            Session::flash('success', "Votre mot de passe a bien été modifié");
                            $_SESSION['USER'] = $user['id'];
                            header('Location: ' . $r->generate('account')); exit;
                        } else { Session::flash('danger', "mot de passe trop court"); }
                    } else { Session::flash('danger', "les mots de passe ne sont pas identiques"); }
                } else { Session::flash('danger', 'Merci de renseigner un mot de passe'); }
            }  
        } else { Session::flash('danger', "Ce token n'est pas valide");
            header('Location: ' . $r->generate('forget')); exit; }
    } else { header('Location: ' . $r->generate('login')); exit; }

?>

<div class="container">
    <div class="col-md-10 m-auto">
        <div class="card text-center m-4 shadow-sm p-3 mb-5 bg-white rounded">
            <div class="card-body">
                <h4 class="card title p-3">Rénitialiser mon mot de passe</h4>
                <div class="form-group">
                    <form action="" method="post">
                        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                        <br>
                        <input type="password" class="form-control" name="password_repeat" placeholder="Rétaper le mot de passe" required>
                        <button type="submit" class="btn btn-primary btn-lg m-3">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
