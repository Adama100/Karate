<?php

use App\App;
use App\Domain\Auth\User;
use App\Session;

Session::getSession();
    $id = (int)$_GET['id'];
    $token = htmlspecialchars($_GET['sign_token']);
    $pdo = App::getPDO();

    $query = $pdo->prepare('SELECT id, token FROM users WHERE id = ?');
    $query->execute([$id]);
    $query->setFetchMode(PDO::FETCH_CLASS, User::class);
    $user = $query->fetch();

    if($user && $user->getToken() == $token) {
        $users = $pdo->prepare('UPDATE users SET token = null, sign_at = NOW() WHERE id = ?');
        $users->execute([$user->getId()]);
        Session::flash('success', "Vous compte a bien été confirmé");
        $_SESSION['USER'] = $user->getId();
        header('Location: ' . $r->generate('home')); exit;
    } else {
        Session::flash('danger', "Ce token n'est pas valide");
        header('Location: ' . $r->generate('login')); exit;
    }