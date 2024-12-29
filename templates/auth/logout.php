<?php

use App\Domain\Application\Session\PHPSession;

PHPSession::get();
    unset($_SESSION['USER']);
    setcookie('auth', '', time() - 3600, '/', 'localhost', true, true);
    header('Location: ' . $r->generate('index'));