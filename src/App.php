<?php

    namespace App;

use App\Domain\Application\Session\PHPSession;
use App\Domain\Auth\Auth;

    class App {

        public static $pdo;
        public static $auth;

        /**
         * Fais la connexion à la base de données
         * @return \PDO
        */
        public static function getPDO(): \PDO
        {
            if(!self::$pdo) {
                self::$pdo = new \PDO('mysql:dbname=karate;host=localhost', 'root', '', [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]);
            }
            return self::$pdo;
        }

        /**
         * Retourne une instance unique de Auth
         * @return \App\Domain\Auth\Auth
        */
        public static function getAuth(): Auth
        {
            if(!self::$auth) {
                if(session_status() === PHP_SESSION_NONE) {
                    PHPSession::get();
                }
                self::$auth = new Auth(self::getPDO(), $_SESSION); 
            }
            return self::$auth;
        }

    }