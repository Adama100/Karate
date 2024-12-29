<?php

use App\App;
use App\Domain\Application\Session\PHPSession;
use App\Domain\Auth\Security\UserChecker;

PHPSession::get();
UserChecker::AdminCheck($r->generate('login'));

    $title = "Administration | Utilisateurs";
    $pdo = App::getPDO();

    $clubs = $pdo->query("SELECT COUNT(id) as count FROM club")->fetchColumn();
    $users = $pdo->query("SELECT COUNT(id) as count FROM users WHERE sign_at IS NOT NULL AND role != 'super admin'")->fetchColumn();

?>

<div class="container">
    <h5 class="display-6 mb-4">Administration</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    Clubs
                </div>
                <div class="card-footer">
                    <a href="<?= $r->generate('admin.clubs') ?>" class="btn btn-lg btn-primary">
                        <?= $clubs ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    Utilisateurs
                </div>
                <div class="card-footer">
                    <a href="<?= $r->generate('admin.users') ?>" class="btn btn-lg btn-primary">
                        <?= $users ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>