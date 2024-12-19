<?php

    namespace App\Helper;

use App\Session;

    class RedirectFlash {

        /**
         * Redirige avec un flash
         * @param string $message
         * @param string $path
         * @return never
        */
        public static function flash(string $type, string $message, string $path)
        {
            Session::flash($type, $message);
            header('Location: ' . $path); exit;
        }

    }