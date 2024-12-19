<?php

use App\Session;

    Session::getSession(); 
    unset($_SESSION['USER']);
    setcookie('auth', '', time() - 3600, '/', 'localhost', true, true);
    header('Location: ' . $r->generate('index'));