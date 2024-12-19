<?php

    namespace App\Domain\Security;

    class TokenCSRF {

        /**
         * Générer un token CSRF
         * @return mixed|string
        */
        public static function generateToken()
        {
            if (empty($_SESSION['csrf'])) {
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
            }
            return $_SESSION['csrf'];
        }

        /**
         * Inclure un champ caché avec le token dans les formulaires
         * @return string
        */
        public static function getFormField(): string
        {
            $token = self::generateToken();
            return '<input type="hidden" name="csrf" value="' . htmlspecialchars($token) . '">';
        }

        /**
         * Valider le token CSRF
         * @param mixed $token
         * @return bool
        */
        public static function validateToken($token): bool
        {
            return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
        }

    }