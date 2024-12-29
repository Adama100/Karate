<?php

    namespace App\Domain\Application\Session;

    class Flash {

        /**
         * Créer un message flash
         * @param string $key
         * @param string $message
         * @return void
        */
        public static function flash(string $key, string $message): void
        {
            if(session_status() === PHP_SESSION_NONE) {
                PHPSession::get();
            }
            $_SESSION['flash'][$key] = $message;
        }

        /**
         * Créer un tableau de messages flash
         * @param string $message
         * @return void
        */
        public static function gflash(string $message): void
        {
            if(session_status() === PHP_SESSION_NONE) {
                PHPSession::get();
            }
            $_SESSION['gflash'][] = $message;
        }

    }