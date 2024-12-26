<?php

use App\App;
use App\Domain\Club\ClubTable;
use App\Domain\Security\UserChecker;
use App\Session;

Session::getSession();
UserChecker::AdminCheck($r->generate('login'));

    $id = (int)$params['id'];
    $pdo = App::getPDO();
    $clubs = new ClubTable($pdo);
    $pdo->beginTransaction();
    try {
        $club = $clubs->find($id);
        $clubs->delete($club->getId());
        $pdo->commit();
        Session::flash('success', 'le club a été supprimé avec succès');
    } catch(Exception $e) {
        $pdo->rollBack();
        Session::flash('danger', $e);
    }
    header('Location: ' . $r->generate('admin.clubs')); exit;