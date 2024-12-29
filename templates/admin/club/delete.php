<?php

use App\App;
use App\Domain\Application\Session\Flash;
use App\Domain\Application\Session\PHPSession;
use App\Domain\Auth\Security\UserChecker;
use App\Domain\Club\Repository\ClubRepository;

PHPSession::get();
UserChecker::AdminCheck($r->generate('login'));

    $id = (int)$params['id'];
    $pdo = App::getPDO();
    $clubs = new ClubRepository($pdo);
    $pdo->beginTransaction();
    try {
        $club = $clubs->find($id);
        $clubs->delete($club->getId());
        $pdo->commit();
        Flash::flash('success', 'le club a été supprimé avec succès');
    } catch(Exception $e) {
        $pdo->rollBack();
        Flash::flash('danger', $e);
    }
    header('Location: ' . $r->generate('admin.clubs')); exit;