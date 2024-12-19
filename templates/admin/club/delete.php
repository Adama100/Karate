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
    try {
        $club = $clubs->find($id);
        $clubs->delete($pagne->getId());
    } catch(Exception $e) {
        Session::flash('danger', $e);
    }
    Session::flash('success', 'le club a été supprimé avec succès');
    header('Location: ' . $r->generate('admin.clubs')); exit;