<?php

    namespace App;

    class Session {

        public static $session; 

        private function __construct()
        {
            session_start();
        }

        public static function getSession(): Session
        {
            if(!self::$session) {
                self::$session = new Session();
            }
            return self::$session;
        }

        /**
         * Utiliser pour faire des messages flash
         * @param string $key
         * @param string $message
         * @return void
        */
        public static function flash(string $key, string $message): void
        {
            if(session_status() === PHP_SESSION_NONE) {
                self::getSession();
            }
            $_SESSION['flash'][$key] = $message;
        }

        public static function gflash(string $message): void
        {
            if(session_status() === PHP_SESSION_NONE) {
                self::getSession();
            }
            $_SESSION['gflash'][] = $message;
        }

    }