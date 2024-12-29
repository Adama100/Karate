<?php

    namespace App\Domain\Application\Session;

    class PHPSession {

        public static $session; 

        private function __construct()
        {
            session_start();
        }

        public static function get(): PHPSession
        {
            if(!self::$session) {
                self::$session = new PHPSession();
            }
            return self::$session;
        }

    }