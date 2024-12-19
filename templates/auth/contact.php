<?php

use App\App;
use App\Domain\Auth\ContactDTO;
use App\Domain\Security\TokenCSRF;
use App\Domain\Security\UserChecker;
use App\Session;

Session::getSession();
UserChecker::UserNotConnect($r->generate('index'));
    $title = "Nous contactez";
    $nav = "contact";

    $pdo = App::getPDO();
    $user = App::getAuth()->user();

    if(!empty($_POST)) {

        if (!TokenCSRF::validateToken($_POST['csrf'])) {
            Session::flash('danger', 'Token CSRF invalide');
            header('Location: ' . $r->generate('contact')); exit;
        }
        $contact = new ContactDTO($pdo, $_POST['email'], $_POST['message']);
        $contact->contactDTO($user->getId());
    }

?>

<main class="container">
    <form action="" method="post">
        <?= TokenCSRF::getFormField() ?>
        <fieldset><legend class="text-center text-secondary mt-3 mb-3 fw-bold">Nous contactez</legend>
            <div class="form-floating mb-3 mx-2">
                <input type="email" class="form-control shadow-sm rounded-3" autocomplete="off" id="email" name="email" placeholder="" value="<?php if($user): ?><?= $user->getEmail() ?><?php endif; ?>" required>
                <label for="email" class="fw-light" style="color:goldenrod;">Votre email</label>
            </div>
            <div class="form-floating mb-3 mx-2">
                <textarea class="form-control focus-ring shadow-sm rounded-3" style="height: 10rem;" placeholder="" id="message" required name="message"></textarea>
                <label for="message" class="fw-light" style="color:goldenrod;">Votre message</label>
            </div>
            <button type="submit" class="btn btn-warning fw-bold shadow-sm d-flex flex-row mt-1 mb-4 px-5 m-auto">Envoyer</button>
        </fieldset>
    </form>
</main>