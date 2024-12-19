<?php

    require dirname(__DIR__) . '/vendor/autoload.php';

    // DEBUG
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register(); 
    define('DEBUG_TIME', microtime(true));

    $cssVersion = filemtime('dist/app.css');
    $jsVersion = filemtime('dist/app.js');
    date_default_timezone_set('Africa/Abidjan');

    $r = new AltoRouter();

    $r->setBasePath('/KRT/public');

    $r->map('GET', '/', 'templates/home/index', 'index');
    // Auth
    $r->map('GET|POST', '/connexion', 'templates/auth/login', 'login');
    $r->map('GET|POST', '/inscription', 'templates/auth/sign', 'sign');
    $r->map('GET', '/deconnexion', 'templates/auth/out', 'out');
    $r->map('GET', '/inscription/confirmer', 'templates/auth/ckeck', 'sign.check');
    $r->map('GET|POST', '/contact', 'templates/auth/contact', 'contact');
    $r->map('GET|POST', '/mot_de_passe', 'templates/auth/forget', 'forget');
    $r->map('GET|POST', '/renitialiser', 'templates/auth/reset', 'reset');
    // Account
    $r->map('GET|POST', '/mon-compte', 'templates/account/index', 'account');
    // Admin
    $r->map('GET', '/administration', 'templates/admin/index', 'admin');
    $r->map('GET', '/administration/clubs', 'templates/admin/club/index', 'admin.clubs');



    $match = $r->match();

    if(is_array($match)) {
        if(is_callable($match['target'])) {
            call_user_func_array($match['target'], $match['params']);
        } else{
            $view = $match['target'];
            $params = $match['params'];
            $isAdmin = strpos($view, 'admin/') !== false;
            $layout = $isAdmin ? 'admin/layout/default' : 'layout/default';
            ob_start();
            require "../{$match['target']}.php";
            $content = ob_get_clean();
        }
        require '../templates' . DIRECTORY_SEPARATOR . $layout . '.php';
    } else {
        require '../templates/layout/error.php';
    }