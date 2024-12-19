<?php

use App\App;
use App\Domain\Security\UserChecker;
use App\Session;

Session::getSession();
UserChecker::AdminCheck($r->generate('login'));

    $title = "Admin";
    $pdo = App::getPDO();

    $clubs = $pdo->prepare('SELECT COUNT(id) as count FROM club');
    $clubs->execute();
    $clubs = $clubs->fetch()['count'];

    $users = $pdo->prepare("SELECT COUNT(id) as count FROM users WHERE sign_at IS NOT NULL AND role != 'super admin'");
    $users->execute();
    $users = $users->fetch()['count'];

?>

<div class="container">
    <h5 class="display-6 mb-4">Administration</h5>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    Clubs
                </div>
                <div class="card-footer"><a href="" class="btn btn-lg btn-primary"><?= $clubs ?></a></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    Utilisateurs
                </div>
                <div class="card-footer"><a href="" class="btn btn-lg btn-primary"><?= $users ?></a></div>
            </div>
        </div>
    </div>
</div>